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
        Schema::create('gate_pass', function (Blueprint $table) {
            $table->id();
            $table->index('project_id');
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            // $table->index('plot_id');
            $table->unsignedBigInteger('plot_id')->nullable();
            // $table->foreign('plot_id')->references('id')->on('plots')->onDelete('cascade');
            $table->index('director_id');
            $table->unsignedBigInteger('director_id');
            $table->foreign('director_id')->references('id')->on('directors')->onDelete('cascade');
            $table->index('marketer_id');
            $table->unsignedBigInteger('marketer_id');
            $table->foreign('marketer_id')->references('id')->on('marketers')->onDelete('cascade');
            $table->string('customer_name');
            $table->bigInteger('mobile_no')->nullable();
            $table->date('date')->nullable();
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gate_pass');
    }
};
