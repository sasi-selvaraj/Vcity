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
        Schema::create('customer_booking', function (Blueprint $table) {
            $table->id();
            $table->integer('project_id');
            $table->integer('plot_id');
            $table->integer('director_id');
            $table->enum('status', ['Booked','Hold','Paid'])->default('Booked');
            $table->string('customer_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_booking');
    }
};
