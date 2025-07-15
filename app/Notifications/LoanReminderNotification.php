<?php

namespace App\Notifications;

use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $loan;
    public $reminderType;

    // Reminder-Typen
    const TYPE_WEEK_REMINDER = 'week_reminder';  // 1 Woche vorher
    const TYPE_DAY_REMINDER = 'day_reminder';  // 1 Tag vorher
    const TYPE_DUE_TODAY = 'due_today';  // Heute fällig
    const TYPE_OVERDUE = 'overdue';  // Überfällig

    /**
     * Create a new notification instance.
     */
    public function __construct(Loan $loan, string $reminderType)
    {
        $this->loan = $loan;
        $this->reminderType = $reminderType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $loan = $this->loan;
        $book = $loan->book;
        $dueDate = $loan->due_date;
        $daysUntilDue = $dueDate->diffInDays(Carbon::now());

        return (new MailMessage)
            ->subject($this->getSubject())
            ->markdown('emails.loan-reminder', [
                'loan' => $loan,
                'notifiable' => $notifiable,
                'subject' => $this->getSubject(),
                'mainMessage' => $this->getMainMessage(),
                'additionalInfo' => $this->getAdditionalInfo(),
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'loan_id' => $this->loan->id,
            'book_title' => $this->loan->book->title,
            'due_date' => $this->loan->due_date->format('Y-m-d'),
            'reminder_type' => $this->reminderType,
            'message' => $this->getMainMessage(),
        ];
    }

    /**
     * Get the notification subject based on reminder type
     */
    private function getSubject(): string
    {
        switch ($this->reminderType) {
            case self::TYPE_WEEK_REMINDER:
                return '📚 Erinnerung: Buch-Rückgabe in einer Woche';
            case self::TYPE_DAY_REMINDER:
                return '📚 Wichtig: Buch-Rückgabe morgen';
            case self::TYPE_DUE_TODAY:
                return '📚 Heute fällig: Buch-Rückgabe';
            case self::TYPE_OVERDUE:
                return '⚠️ Überfällig: Buch-Rückgabe';
            default:
                return '📚 Erinnerung: Buch-Rückgabe';
        }
    }

    /**
     * Get the main message based on reminder type
     */
    private function getMainMessage(): string
    {
        switch ($this->reminderType) {
            case self::TYPE_WEEK_REMINDER:
                return 'Dein ausgeliehenes Buch muss in einer Woche zurückgegeben werden.';
            case self::TYPE_DAY_REMINDER:
                return 'Dein ausgeliehenes Buch muss morgen zurückgegeben werden. Vergiss es nicht!';
            case self::TYPE_DUE_TODAY:
                return 'Dein ausgeliehenes Buch muss heute zurückgegeben werden.';
            case self::TYPE_OVERDUE:
                return 'Dein ausgeliehenes Buch ist überfällig und sollte schnellstmöglich zurückgegeben werden.';
            default:
                return 'Erinnerung für dein ausgeliehenes Buch.';
        }
    }

    /**
     * Get additional information based on reminder type
     */
    private function getAdditionalInfo(): string
    {
        switch ($this->reminderType) {
            case self::TYPE_WEEK_REMINDER:
                return 'Du hast noch genügend Zeit, aber es ist gut, sich schon mal darauf vorzubereiten.';
            case self::TYPE_DAY_REMINDER:
                return 'Bitte kontaktiere den Buchbesitzer, um die Rückgabe zu koordinieren.';
            case self::TYPE_DUE_TODAY:
                return 'Bitte gib das Buch heute zurück oder kontaktiere den Besitzer wegen einer Verlängerung.';
            case self::TYPE_OVERDUE:
                return 'Bitte gib das Buch so schnell wie möglich zurück. Bei Problemen kontaktiere den Besitzer.';
            default:
                return '';
        }
    }

    /**
     * Get reminder types with their descriptions
     */
    public static function getReminderTypes(): array
    {
        return [
            self::TYPE_WEEK_REMINDER => 'Eine Woche vorher',
            self::TYPE_DAY_REMINDER => 'Ein Tag vorher',
            self::TYPE_DUE_TODAY => 'Am Fälligkeitstag',
            self::TYPE_OVERDUE => 'Überfällig',
        ];
    }
}
