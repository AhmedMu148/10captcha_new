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
        Schema::create('pym_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('uid')->index();
            $table->unsignedBigInteger('payment_id')->nullable()->index();
            $table->decimal('value', 8, 2)->default(0);
            $table->tinyInteger('gtm_event')->default(0)->index();
            $table->timestamps();

            // optional foreign key (commented out to match legacy structure)
            // $table->foreign('uid')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pym_events');
    }
};
