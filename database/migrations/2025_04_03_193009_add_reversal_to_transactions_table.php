<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
        DB::statement("ALTER TABLE transactions MODIFY COLUMN transaction_type ENUM('deposit', 'withdrawal', 'transfer','expenses','commission','loan_credit', 'loan_debit', 'reversal') NOT NULL");
        DB::statement("ALTER TABLE customer_transactions MODIFY COLUMN transaction_type ENUM('deposit', 'withdrawal', 'transfer','expenses','commission','loan_credit', 'loan_debit','reversal') NOT NULL");
       
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('reversed_by')->nullable()->constrained('transactions');
            $table->foreignId('reverses_id')->nullable()->constrained('transactions');
        });

        Schema::table('customer_transactions', function (Blueprint $table) {
            $table->foreignId('commission_id')->nullable()->constrained('customer_transactions');
            $table->foreignId('reversed_by')->nullable()->constrained('customer_transactions');
            $table->foreignId('reverses_id')->nullable()->constrained('customer_transactions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        DB::statement("ALTER TABLE transactions MODIFY COLUMN transaction_type ENUM('deposit', 'withdrawal', 'transfer','expenses','commission','loan_credit', 'loan_debit') NOT NULL");
        DB::statement("ALTER TABLE customer_transactions MODIFY COLUMN transaction_type ENUM('deposit', 'withdrawal', 'transfer','expenses','commission','loan_credit', 'loan_debit') NOT NULL");
        
        Schema::table('transactions', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['reversed_by']);
            $table->dropForeign(['reverses_id']);
            
            // Then drop the columns
            $table->dropColumn('reversed_by');
            $table->dropColumn('reverses_id');
        });

        Schema::table('customer_transactions', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['reversed_by']);
            $table->dropForeign(['reverses_id']);
            $table->dropForeign(['commission_id']);
            
            // Then drop the columns
            $table->dropColumn('reversed_by');
            $table->dropColumn('reverses_id');
            $table->dropColumn('commission_id');
        });
    }
};
