<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('fname', 55)->nullable();
            $table->string('lname', 55)->nullable();
            $table->string('country', 155)->nullable();
            $table->string('mobile', 32)->nullable();
            $table->integer('mobile_verify')->default(0);
            $table->string('ref_url', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
