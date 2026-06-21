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
        Schema::create('affiliate_withdraws', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('txn_id')->unique();
            $table->integer('amount_5d');
            $table->enum('method', ['PayPal', 'Payoneer', 'Bitcoin', 'Neteller', 'Skrill', 'Account Balance']);
            $table->string('payment_email');
            $table->enum('status', ['Awaiting', 'Approve', 'Unapprove'])->default('Awaiting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_withdraws');
    }
};
