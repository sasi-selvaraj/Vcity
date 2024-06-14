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
        Schema::create('plots', function (Blueprint $table) {
            $table->id();
            $table->index('project_id');
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->string('plot_no')->nullable();
            $table->string('block')->nullable();
            $table->string('facing')->nullable();
            $table->decimal('plot_sqft', 12, 2)->nullable();
            // $table->decimal('sqft_rate', 12, 2)->nullable();
            $table->string('location')->nullable();
            $table->decimal('glv_rate', 12, 2)->nullable();
            $table->decimal('glv', 12, 2)->nullable();
            $table->decimal('dev_rate', 12, 2)->nullable();
            $table->decimal('development_charges', 12, 2)->nullable();
            $table->string('gst')->nullable();
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->decimal('balance_amount', 12, 2)->nullable();
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->enum('status',['Available','Hold','Temporary Booking','Booking','Full Payment','Registered'])->default('Available');
            $table->timestamp('status_updated_at')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plots');
    }
};
