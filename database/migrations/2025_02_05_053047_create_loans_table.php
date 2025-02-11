<?php

use App\Enums\LoanDuration;
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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('min_amount');
            $table->string('max_amount', 12, 2);
            $table->string('interest_rate', 5, 2)->default(8.90);
            $table->string('late_payment_interest_rate', 5, 2)->default(1.00);
            $table->enum('min_duration', LoanDuration::toArray())->default(LoanDuration::ONE_MONTH);
            $table->enum('max_duration', LoanDuration::toArray())->default(LoanDuration::SIX_MONTH);
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
