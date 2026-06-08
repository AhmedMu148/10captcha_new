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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('gateway');
            $table->string('payment_provider');
            $table->string('payment_reference')->nullable();
            $table->string('payment_hash')->nullable();
            $table->string('txn')->nullable();
            $table->unsignedBigInteger('amount'); // stored x100000
            $table->tinyInteger('status')->default(0); // 0=uncompleted, 1=completed, 2=canceled
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
