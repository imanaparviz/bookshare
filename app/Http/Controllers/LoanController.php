<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoanController extends Controller
{
    /**
     * نمایش فهرست امانت‌های کاربر احراز هویت شده
     */
    public function index(): View
    {
        $borrowedBooks = Loan::where('borrower_id', auth()->user()->id)
            ->with(['book', 'lender'])
            ->latest()
            ->get();

        $lentBooks = Loan::where('lender_id', auth()->user()->id)
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
            return back()->with('error', 'این کتاب در دسترس نیست.');
        }

        // بررسی اینکه آیا کاربر مالک کتاب نیست
        if ($book->owner_id === auth()->id()) {
            return back()->with('error', 'شما نمی‌توانید کتاب خود را امانت بگیرید.');
        }

        // ایجاد درخواست امانت
        Loan::create([
            'book_id' => $book->id,
            'borrower_id' => auth()->id(),
            'lender_id' => $book->owner_id,
            'loan_date' => Carbon::now(),
            'due_date' => Carbon::now()->addWeeks(2),  // پیش‌فرض ۲ هفته
            'status' => Loan::STATUS_ANGEFRAGT,
            'notes' => $request->notes,
        ]);

        // به‌روزرسانی وضعیت کتاب
        $book->update(['status' => Book::STATUS_ANGEFRAGT]);

        return back()->with('success', 'درخواست امانت با موفقیت ارسال شد!');
    }

    /**
     * به‌روزرسانی وضعیت امانت (تایید/رد/برگرداندن)
     */
    public function update(Request $request, Loan $loan): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:approve,deny,return',
            'notes' => 'nullable|string|max:500',
        ]);

        // بررسی اینکه آیا کاربر امانت‌دهنده است
        if ($loan->lender_id !== auth()->id() && $request->action !== 'return') {
            abort(403, 'شما مجوز انجام این عمل را ندارید.');
        }

        // بررسی اینکه آیا کاربر امانت‌گیرنده است برای عمل برگرداندن
        if ($request->action === 'return' && $loan->borrower_id !== auth()->id()) {
            abort(403, 'شما فقط می‌توانید امانت‌های خود را برگردانید.');
        }

        switch ($request->action) {
            case 'approve':
                $loan->update([
                    'status' => Loan::STATUS_AKTIV,
                    'loan_date' => Carbon::now(),
                    'notes' => $request->notes,
                ]);
                $loan->book->update(['status' => Book::STATUS_AUSGELIEHEN]);
                $message = 'درخواست امانت تایید شد!';
                break;

            case 'deny':
                $loan->update([
                    'status' => Loan::STATUS_ABGELEHNT,
                    'notes' => $request->notes,
                ]);
                $loan->book->update(['status' => Book::STATUS_VERFUEGBAR]);
                $message = 'درخواست امانت رد شد!';
                break;

            case 'return':
                $loan->update([
                    'status' => Loan::STATUS_ZURUECKGEGEBEN,
                    'return_date' => Carbon::now(),
                    'notes' => $request->notes,
                ]);
                $loan->book->update(['status' => Book::STATUS_VERFUEGBAR]);
                $message = 'کتاب با موفقیت برگردانده شد!';
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
        if ($loan->borrower_id !== auth()->id() && $loan->lender_id !== auth()->id()) {
            abort(403, 'شما مجوز مشاهده این امانت را ندارید.');
        }

        return view('loans.show', compact('loan'));
    }
}
