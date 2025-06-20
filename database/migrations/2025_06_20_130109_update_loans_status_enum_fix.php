<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * اجرای مایگریشن‌ها
     */
    public function up(): void
    {
        // چون SQLite از ALTER COLUMN به خوبی پشتیبانی نمی‌کند، از روش دیگری استفاده می‌کنیم
        // جدول را حذف کرده و با شما درست مجدداً ایجاد می‌کنیم

        // ایجاد پشتیبان از داده‌ها
        $loans = DB::table('loans')->get();

        // حذف جدول
        Schema::dropIfExists('loans');

        // ایجاد مجدد جدول با فیلد status درست
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('borrower_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('lender_id')->constrained('users')->onDelete('cascade');
            $table->date('loan_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            // استفاده از string به جای enum برای سازگاری بهتر
            $table->string('status', 20)->default('angefragt');
            $table->text('notes')->nullable();
            $table->timestamps();

            // اندکس‌ها برای بهبود عملکرد
            $table->index(['book_id', 'status']);
            $table->index(['borrower_id', 'status']);
            $table->index('due_date');
        });

        // بازگردانی داده‌ها
        foreach ($loans as $loan) {
            DB::table('loans')->insert((array) $loan);
        }
    }

    /**
     * بازگشت مایگریشن‌ها
     */
    public function down(): void
    {
        // بازگشت: برگشت به enum اصلی
        $loans = DB::table('loans')->get();

        Schema::dropIfExists('loans');

        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('borrower_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('lender_id')->constrained('users')->onDelete('cascade');
            $table->date('loan_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['aktiv', 'angefragt', 'zurückgegeben', 'überfällig'])->default('aktiv');
            $table->text('notes')->nullable();
            $table->timestamps();

            // اندکس‌ها برای بهبود عملکرد
            $table->index(['book_id', 'status']);
            $table->index(['borrower_id', 'status']);
            $table->index('due_date');
        });

        foreach ($loans as $loan) {
            DB::table('loans')->insert((array) $loan);
        }
    }
};
