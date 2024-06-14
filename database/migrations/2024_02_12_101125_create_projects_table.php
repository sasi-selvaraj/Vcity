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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_number')->unique()->nullable();
            $table->string('project_name')->nullable();
            $table->string('project_location')->nullable();
            $table->tinyInteger('total_blocks')->nullable();
            $table->decimal('total_no_of_sqft', 12, 2)->nullable();
            $table->integer('total_plots')->default(0);
            $table->longText('project_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
