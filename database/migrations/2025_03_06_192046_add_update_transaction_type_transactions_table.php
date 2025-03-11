<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY COLUMN transaction_type ENUM('deposit', 'withdrawal', 'transfer','expenses','commission','loan_credit', 'loan_debit') NOT NULL");
        DB::statement("ALTER TABLE customer_transactions MODIFY COLUMN transaction_type ENUM('deposit', 'withdrawal', 'transfer','expenses','commission','loan_credit', 'loan_debit') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY COLUMN transaction_type ENUM('deposit', 'withdrawal', 'transfer','expenses','commission', 'withdrawal') NOT NULL");
        DB::statement("ALTER TABLE customer_transactions MODIFY COLUMN transaction_type ENUM('deposit', 'withdrawal', 'transfer','expenses','commission', 'withdrawal') NOT NULL");
    }
};
