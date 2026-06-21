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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('f_name');
            $table->string('l_name');
            $table->string('software_name');
            $table->string('software_link');
            $table->text('message');
            $table->string('hash')->unique()->nullable();
            $table->string('promo_link')->nullable();
            $table->enum('status', ['Awaiting', 'Approve', 'Unapprove'])->default('Awaiting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
