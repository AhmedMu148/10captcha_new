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
        Schema::create('affiliate_commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aff_id');
            $table->unsignedBigInteger('aff_rel_id');
            $table->integer('comm_amount_5d');
            $table->integer('comm_percent');
            $table->enum('status', ['Awaiting', 'Approve', 'Unapprove'])->default('Awaiting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_commissions');
    }
};
