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
        Schema::create('custom_images_tests', function (Blueprint $table) {
            $table->id();
            $table->integer('uid');
            $table->longText('base64');
            $table->string('module', 155);
            $table->string('result', 512);
            $table->string('result_ocr', 512)->nullable();
            $table->string('hash', 155);
            $table->integer('loop');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_images_tests');
    }
};
