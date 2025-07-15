<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Loan;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConversationController extends Controller
{
    /**
     * Zeige alle Conversations des aktuellen Benutzers
     */
    public function index(): View
    {
        $conversations = Conversation::forUser(Auth::user())
            ->active()
            ->withLatestMessage()
            ->orderBy('last_message_at', 'desc')
            ->get();

        return view('conversations.index', compact('conversations'));
    }

    /**
     * Zeige eine spezifische Conversation
     */
    public function show(Conversation $conversation): View
    {
        // Prüfe ob Benutzer Teilnehmer ist
        if (!$conversation->isParticipant(Auth::user())) {
            abort(403, 'Sie haben keine Berechtigung, diese Unterhaltung zu sehen.');
        }

        // Lade Nachrichten mit Pagination
        $messages = $conversation
            ->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        // Markiere ungelesene Nachrichten als gelesen
        $conversation
            ->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        // Aktualisiere last_seen für Online-Präsenz
        Auth::user()->updateLastSeen();

        $otherParticipant = $conversation->getOtherParticipant(Auth::user());

        return view('conversations.show', compact('conversation', 'messages', 'otherParticipant'));
    }

    /**
     * Erstelle eine neue Conversation basierend auf einer Ausleihe
     */
    public function createForLoan(Loan $loan): RedirectResponse
    {
        // Prüfe ob Benutzer an der Ausleihe beteiligt ist
        if ($loan->borrower_id !== Auth::id() && $loan->lender_id !== Auth::id()) {
            abort(403, 'Sie haben keine Berechtigung, eine Unterhaltung zu dieser Ausleihe zu erstellen.');
        }

        // Erstelle oder finde die Conversation
        $conversation = Conversation::findOrCreateForLoan($loan);

        // Erstelle eine System-Nachricht wenn neu
        if ($conversation->wasRecentlyCreated) {
            Message::createSystemMessage(
                $conversation,
                Message::TYPE_SYSTEM,
                'Unterhaltung zu "' . $loan->book->title . '" gestartet'
            );

            $conversation->update(['last_message_at' => now()]);
        }

        return redirect()->route('conversations.show', $conversation);
    }

    /**
     * Sende eine neue Nachricht
     */
    public function sendMessage(Request $request, Conversation $conversation): RedirectResponse
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'type' => 'nullable|in:text,loan_request,loan_approved,loan_denied,book_returned',
        ]);

        // Prüfe ob Benutzer Teilnehmer ist
        if (!$conversation->isParticipant(Auth::user())) {
            abort(403, 'Sie haben keine Berechtigung, in diese Unterhaltung zu schreiben.');
        }

        // Erstelle die Nachricht
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'content' => $request->content,
            'type' => $request->type ?? Message::TYPE_TEXT,
        ]);

        // Aktualisiere die Conversation
        $conversation->update([
            'last_message_at' => now(),
            'is_active' => true,
        ]);

        // Aktualisiere Online-Status
        Auth::user()->updateLastSeen();

        return back()->with('success', 'Nachricht gesendet!');
    }

    /**
     * Markiere alle Nachrichten als gelesen
     */
    public function markAllAsRead(Conversation $conversation): RedirectResponse
    {
        // Prüfe ob Benutzer Teilnehmer ist
        if (!$conversation->isParticipant(Auth::user())) {
            abort(403, 'Sie haben keine Berechtigung.');
        }

        // Markiere alle ungelesenen Nachrichten als gelesen
        $conversation
            ->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return back()->with('success', 'Alle Nachrichten als gelesen markiert!');
    }

    /**
     * Archiviere eine Conversation
     */
    public function archive(Conversation $conversation): RedirectResponse
    {
        // Prüfe ob Benutzer Teilnehmer ist
        if (!$conversation->isParticipant(Auth::user())) {
            abort(403, 'Sie haben keine Berechtigung.');
        }

        $conversation->update(['is_active' => false]);

        return back()->with('success', 'Unterhaltung archiviert!');
    }

    /**
     * Zeige alle Conversations für eine spezifische Ausleihe
     */
    public function showForLoan(Loan $loan): RedirectResponse
    {
        // Prüfe ob Benutzer an der Ausleihe beteiligt ist
        if ($loan->borrower_id !== Auth::id() && $loan->lender_id !== Auth::id()) {
            abort(403, 'Sie haben keine Berechtigung.');
        }

        $conversation = $loan->conversation;

        if (!$conversation) {
            // Erstelle eine neue Conversation
            $conversation = Conversation::findOrCreateForLoan($loan);
        }

        return redirect()->route('conversations.show', $conversation);
    }

    /**
     * API-Endpunkt für ungelesene Nachrichten-Zählung
     */
    public function getUnreadCount(): array
    {
        $count = Auth::user()->getUnreadMessagesCount();

        return [
            'unread_count' => $count,
            'has_unread' => $count > 0
        ];
    }

    /**
     * Schnellnachricht mit vorgefertigten Templates
     */
    public function sendQuickMessage(Request $request, Conversation $conversation): RedirectResponse
    {
        $request->validate([
            'template' => 'required|in:pickup_ready,pickup_confirmed,thanks,book_condition,delay_request',
        ]);

        $templates = [
            'pickup_ready' => '📚 Das Buch ist zur Abholung bereit!',
            'pickup_confirmed' => '✅ Abholung bestätigt. Bis bald!',
            'thanks' => '🙏 Vielen Dank für das Ausleihen!',
            'book_condition' => '📖 Wie ist der Zustand des Buches?',
            'delay_request' => '⏰ Könnten Sie die Ausleihe um ein paar Tage verlängern?',
        ];

        // Prüfe ob Benutzer Teilnehmer ist
        if (!$conversation->isParticipant(Auth::user())) {
            abort(403, 'Sie haben keine Berechtigung.');
        }

        $content = $templates[$request->template];

        // Erstelle die Nachricht
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'content' => $content,
            'type' => Message::TYPE_TEXT,
        ]);

        // Aktualisiere die Conversation
        $conversation->update([
            'last_message_at' => now(),
            'is_active' => true,
        ]);

        return back()->with('success', 'Schnellnachricht gesendet!');
    }
}
