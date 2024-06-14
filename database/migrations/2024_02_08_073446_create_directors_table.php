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
        Schema::create('directors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('director_id');
            $table->string('mobile_no');
            $table->enum('designation', ['Dir', 'SD', 'CD', 'AD'])->default('Dir');
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->timestamp('otp_verified')->nullable();
            $table->integer('otp')->nullable();
            $table->string('address')->nullable();
            $table->string('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('directors');
    }
};
