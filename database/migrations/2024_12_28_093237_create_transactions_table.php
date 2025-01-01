<?php

use App\Enums\PaymentMethod;
use App\Enums\TransactionType;
use App\Enums\Type;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('cascade');
            $table->string('reference')->unique();
            $table->enum('type', Type::toArray());
            $table->enum('payment_method', PaymentMethod::toArray());
            $table->enum('transaction_type', TransactionType::toArray());
            $table->string('amount');
            $table->string('balance_before');
            $table->string('balance_after');
            $table->string('description');
            $table->string('remark')->nullable();
            $table->string('date');
            $table->string('count')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
