<?php

namespace App\Console\Commands;

use App\Models\Loan;
use App\Notifications\LoanReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendLoanReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loans:send-reminders {--test : Test mode - only show what would be sent}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send loan reminder notifications to users about upcoming due dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $testMode = $this->option('test');

        if ($testMode) {
            $this->info('🧪 Running in TEST mode - no notifications will be sent');
        }

        $this->info('📅 Checking for loans that need reminder notifications...');
        $totalSent = 0;

        // Get all active loans
        $activeLoans = Loan::active()
            ->with(['borrower', 'book', 'lender'])
            ->get();

        $this->info("Found {$activeLoans->count()} active loans to check");

        foreach ($activeLoans as $loan) {
            $remindersSent = $this->processLoan($loan, $testMode);
            $totalSent += $remindersSent;
        }

        $this->info("✅ Completed! {$totalSent} notifications " . ($testMode ? 'would be' : 'were') . ' sent');

        // Log the execution
        Log::info('Loan reminders processed', [
            'total_loans_checked' => $activeLoans->count(),
            'notifications_sent' => $totalSent,
            'test_mode' => $testMode
        ]);
    }

    /**
     * Process a single loan and send appropriate reminders
     */
    private function processLoan(Loan $loan, bool $testMode): int
    {
        $sentCount = 0;
        $now = Carbon::now();
        $dueDate = $loan->due_date;
        $daysDiff = $now->diffInDays($dueDate, false);  // false = can be negative

        $this->line("📖 Processing: {$loan->book->title} (Due: {$dueDate->format('d.m.Y')}, Days: {$daysDiff})");

        // Check if overdue
        if ($daysDiff < 0) {
            $sentCount += $this->sendReminderIfNeeded(
                $loan,
                LoanReminderNotification::TYPE_OVERDUE,
                $testMode
            );
        }
        // Check if due today
        elseif ($daysDiff == 0) {
            $sentCount += $this->sendReminderIfNeeded(
                $loan,
                LoanReminderNotification::TYPE_DUE_TODAY,
                $testMode
            );
        }
        // Check if due tomorrow (1 day reminder)
        elseif ($daysDiff == 1) {
            $sentCount += $this->sendReminderIfNeeded(
                $loan,
                LoanReminderNotification::TYPE_DAY_REMINDER,
                $testMode
            );
        }
        // Check if due in 7 days (1 week reminder)
        elseif ($daysDiff == 7) {
            $sentCount += $this->sendReminderIfNeeded(
                $loan,
                LoanReminderNotification::TYPE_WEEK_REMINDER,
                $testMode
            );
        }

        return $sentCount;
    }

    /**
     * Send reminder if not already sent today
     */
    private function sendReminderIfNeeded(Loan $loan, string $reminderType, bool $testMode): int
    {
        $user = $loan->borrower;
        $today = Carbon::now()->format('Y-m-d');

        // Check if notification already sent today for this type
        $existingNotification = $user
            ->notifications()
            ->whereDate('created_at', $today)
            ->where('type', LoanReminderNotification::class)
            ->where('data->loan_id', $loan->id)
            ->where('data->reminder_type', $reminderType)
            ->first();

        if ($existingNotification) {
            $this->line("   ⏭️  Reminder already sent today for {$reminderType}");
            return 0;
        }

        // Send notification
        if ($testMode) {
            $this->line("   📧 TEST: Would send {$reminderType} reminder to {$user->name}");
        } else {
            $user->notify(new LoanReminderNotification($loan, $reminderType));
            $this->line("   📧 Sent {$reminderType} reminder to {$user->name}");
        }

        return 1;
    }
}
