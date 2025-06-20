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
     * Display a listing of loans for the authenticated user.
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
     * Store a new loan request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $book = Book::findOrFail($request->book_id);

        // Check if book is available
        if ($book->status !== 'verfügbar') {
            return back()->with('error', 'Dieses Buch ist nicht verfügbar.');
        }

        // Check if user is not the owner
        if ($book->owner_id === auth()->id()) {
            return back()->with('error', 'Sie können Ihr eigenes Buch nicht ausleihen.');
        }

        // Create loan request
        Loan::create([
            'book_id' => $book->id,
            'borrower_id' => auth()->id(),
            'lender_id' => $book->owner_id,
            'loan_date' => Carbon::now(),
            'due_date' => Carbon::now()->addWeeks(2),  // Default 2 weeks
            'status' => 'angefragt',
            'notes' => $request->notes,
        ]);

        // Update book status
        $book->update(['status' => 'angefragt']);

        return back()->with('success', 'Ausleihantrag wurde erfolgreich gesendet!');
    }

    /**
     * Update the loan status (approve/deny/return).
     */
    public function update(Request $request, Loan $loan): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:approve,deny,return',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if user is the lender
        if ($loan->lender_id !== auth()->id() && $request->action !== 'return') {
            abort(403, 'Sie sind nicht berechtigt, diese Aktion durchzuführen.');
        }

        // Check if user is the borrower for return action
        if ($request->action === 'return' && $loan->borrower_id !== auth()->id()) {
            abort(403, 'Sie können nur Ihre eigenen Ausleihen zurückgeben.');
        }

        switch ($request->action) {
            case 'approve':
                $loan->update([
                    'status' => 'aktiv',
                    'loan_date' => Carbon::now(),
                    'notes' => $request->notes,
                ]);
                $loan->book->update(['status' => 'ausgeliehen']);
                $message = 'Ausleihantrag wurde genehmigt!';
                break;

            case 'deny':
                $loan->update([
                    'status' => 'abgelehnt',
                    'notes' => $request->notes,
                ]);
                $loan->book->update(['status' => 'verfügbar']);
                $message = 'Ausleihantrag wurde abgelehnt!';
                break;

            case 'return':
                $loan->update([
                    'status' => 'zurückgegeben',
                    'return_date' => Carbon::now(),
                    'notes' => $request->notes,
                ]);
                $loan->book->update(['status' => 'verfügbar']);
                $message = 'Buch wurde erfolgreich zurückgegeben!';
                break;
        }

        return back()->with('success', $message);
    }

    /**
     * Show the loan details.
     */
    public function show(Loan $loan): View
    {
        // Check if user is involved in this loan
        if ($loan->borrower_id !== auth()->id() && $loan->lender_id !== auth()->id()) {
            abort(403, 'Sie haben keine Berechtigung, diese Ausleihe zu sehen.');
        }

        return view('loans.show', compact('loan'));
    }
}
