<?php

use App\Enums\Gender;
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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->string('first_name');
            $table->string('surname');
            $table->string('account_id');
            $table->string('middle_name')->nullable();
            $table->string('dob')->nullable();
            $table->enum('sex', Gender::toArray())->nullable();
            $table->string('resident_state')->nullable();
            $table->string('resident_lga')->nullable();
            $table->string('resident_address')->nullable();
            $table->string('occupation')->nullable();
            $table->string('office_address')->nullable();
            $table->string('state')->nullable();
            $table->string('lga')->nullable();
            $table->string('hometown')->nullable();
            $table->string('phone');
            $table->string('next_of_kin')->nullable();
            $table->string('relationship')->nullable();
            $table->string('nok_phone')->nullable();
            $table->string('acc_no')->nullable();
            $table->string('branch')->nullable();
            $table->string('group')->nullable();
            $table->string('sb_card_no_from')->nullable();
            $table->string('sb_card_no_to')->nullable();
            $table->string('sb')->nullable();
            $table->string('initial_unit')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('daily_amount')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
