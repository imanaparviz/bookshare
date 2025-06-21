<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoanController extends Controller
{
    /**
     * نمایش فهرست امانت‌های کاربر احراز هویت شده
     */
    public function index(): View
    {
        $borrowedBooks = Loan::where('borrower_id', Auth::id())
            ->with(['book', 'lender'])
            ->latest()
            ->get();

        $lentBooks = Loan::where('lender_id', Auth::id())
            ->with(['book', 'borrower'])
            ->latest()
            ->get();

        return view('loans.index', compact('borrowedBooks', 'lentBooks'));
    }

    /**
     * ذخیره درخواست امانت جدید
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'notes' => 'nullable|string|max:500',
            'message' => 'nullable|string|max:1000',
            'contact_info' => 'nullable|string|max:255',
            'pickup_method' => 'nullable|string|in:pickup,meet,delivery,discuss',
            'duration' => 'nullable|string',
        ]);

        $book = Book::findOrFail($request->book_id);

        // بررسی اینکه آیا کتاب در دسترس است
        if ($book->status !== Book::STATUS_VERFUEGBAR) {
            return back()->with('error', 'Dieses Buch ist nicht verfügbar.');
        }

        // بررسی اینکه آیا کاربر مالک کتاب نیست
        if ($book->owner_id === Auth::id()) {
            return back()->with('error', 'Sie können Ihr eigenes Buch nicht ausleihen.');
        }

        // Handle duration
        $durationWeeks = 2;  // Default
        if ($request->duration && $request->duration !== 'custom') {
            $durationWeeks = (int) $request->duration;
        } elseif ($request->duration === 'custom') {
            $durationWeeks = 2;  // Default for custom, user can specify in message
        }

        // ایجاد درخواست امانت
        Loan::create([
            'book_id' => $book->id,
            'borrower_id' => Auth::id(),
            'lender_id' => $book->owner_id,
            'loan_date' => Carbon::now(),
            'due_date' => Carbon::now()->addWeeks($durationWeeks),
            'status' => Loan::STATUS_ANGEFRAGT,
            'notes' => $request->notes,
            'message' => $request->message,
            'contact_info' => $request->contact_info,
            'pickup_method' => $request->pickup_method,
            'requested_duration_weeks' => $durationWeeks,
        ]);

        // به‌روزرسانی وضعیت کتاب
        $book->update(['status' => Book::STATUS_ANGEFRAGT]);

        return back()->with('success', 'Ihr detaillierter Ausleihantrag wurde erfolgreich gesendet! Der Buchbesitzer wird sich bald bei Ihnen melden.');
    }

    /**
     * به‌روزرسانی وضعیت امانت (تایید/رد/برگرداندن)
     */
    public function update(Request $request, Loan $loan): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:approve,deny,return,cancel,respond',
            'notes' => 'nullable|string|max:500',
            'lender_response' => 'nullable|string|max:1000',
            'final_action' => 'nullable|in:approve,deny,respond_only',
        ]);

        // بررسی اینکه آیا کاربر امانت‌دهنده است برای اعمال approve/deny
        if ($loan->lender_id !== Auth::id() && ($request->action === 'approve' || $request->action === 'deny')) {
            abort(403, 'Sie sind nicht berechtigt, diese Aktion durchzuführen.');
        }

        // بررسی اینکه آیا کاربر امانت‌گیرنده است برای عمل برگرداندن یا لغو
        if (($request->action === 'return' || $request->action === 'cancel') && $loan->borrower_id !== Auth::id()) {
            abort(403, 'Sie können nur Ihre eigenen Ausleihen zurückgeben oder stornieren.');
        }

        switch ($request->action) {
            case 'approve':
                $loan->update([
                    'status' => Loan::STATUS_AKTIV,
                    'loan_date' => Carbon::now(),
                    'notes' => $request->notes,
                ]);
                $loan->book->update(['status' => Book::STATUS_AUSGELIEHEN]);
                $message = 'Ausleihantrag wurde genehmigt!';
                break;

            case 'deny':
                $loan->update([
                    'status' => Loan::STATUS_ABGELEHNT,
                    'notes' => $request->notes,
                ]);
                $loan->book->update(['status' => Book::STATUS_VERFUEGBAR]);
                $message = 'Ausleihantrag wurde abgelehnt!';
                break;

            case 'return':
                $loan->update([
                    'status' => Loan::STATUS_ZURUECKGEGEBEN,
                    'return_date' => Carbon::now(),
                    'notes' => $request->notes,
                ]);
                $loan->book->update(['status' => Book::STATUS_VERFUEGBAR]);
                $message = 'Buch wurde erfolgreich zurückgegeben!';
                break;

            case 'cancel':
                $loan->update([
                    'status' => Loan::STATUS_STORNIERT,
                    'notes' => $request->notes,
                ]);
                $loan->book->update(['status' => Book::STATUS_VERFUEGBAR]);
                $message = 'Ausleihantrag wurde erfolgreich storniert!';
                break;

            case 'respond':
                // Handle lender response with optional final action
                $updateData = [
                    'lender_response' => $request->lender_response,
                    'responded_at' => Carbon::now(),
                ];

                if ($request->final_action) {
                    switch ($request->final_action) {
                        case 'approve':
                            $updateData['status'] = Loan::STATUS_AKTIV;
                            $updateData['loan_date'] = Carbon::now();
                            $loan->book->update(['status' => Book::STATUS_AUSGELIEHEN]);
                            $message = 'Ausleihantrag wurde genehmigt und Ihre Nachricht wurde gesendet!';
                            break;
                        case 'deny':
                            $updateData['status'] = Loan::STATUS_ABGELEHNT;
                            $loan->book->update(['status' => Book::STATUS_VERFUEGBAR]);
                            $message = 'Ausleihantrag wurde abgelehnt und Ihre Nachricht wurde gesendet!';
                            break;
                        case 'respond_only':
                            $message = 'Ihre Nachricht wurde gesendet! Sie können die Anfrage später genehmigen oder ablehnen.';
                            break;
                    }
                } else {
                    $message = 'Ihre Nachricht wurde gesendet!';
                }

                $loan->update($updateData);
                break;
        }

        return back()->with('success', $message);
    }

    /**
     * نمایش جزئیات امانت
     */
    public function show(Loan $loan): View
    {
        // بررسی اینکه آیا کاربر در این امانت دخیل است
        if ($loan->borrower_id !== Auth::id() && $loan->lender_id !== Auth::id()) {
            abort(403, 'Sie haben keine Berechtigung, diese Ausleihe zu sehen.');
        }

        return view('loans.show', compact('loan'));
    }
}
