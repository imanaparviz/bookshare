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

        // ایجاد درخواست امانت
        Loan::create([
            'book_id' => $book->id,
            'borrower_id' => Auth::id(),
            'lender_id' => $book->owner_id,
            'loan_date' => Carbon::now(),
            'due_date' => Carbon::now()->addWeeks(2),  // پیش‌فرض ۲ هفته
            'status' => Loan::STATUS_ANGEFRAGT,
            'notes' => $request->notes,
        ]);

        // به‌روزرسانی وضعیت کتاب
        $book->update(['status' => Book::STATUS_ANGEFRAGT]);

        return back()->with('success', 'Ausleihantrag wurde erfolgreich gesendet!');
    }

    /**
     * به‌روزرسانی وضعیت امانت (تایید/رد/برگرداندن)
     */
    public function update(Request $request, Loan $loan): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:approve,deny,return,cancel',
            'notes' => 'nullable|string|max:500',
        ]);

        // بررسی اینکه آیا کاربر امانت‌دهنده است
        if ($loan->lender_id !== Auth::id() && $request->action !== 'return') {
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
