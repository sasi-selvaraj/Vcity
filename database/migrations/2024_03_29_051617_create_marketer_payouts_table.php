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
        Schema::create('marketer_payouts', function (Blueprint $table) {
            $table->id();
            $table->index('marketer_id');
            $table->unsignedBigInteger('marketer_id');
            $table->foreign('marketer_id')->references('id')->on('marketers');
            $table->string('marketer_vcity_id');
            $table->index('project_id');
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->index('plot_id');
            $table->unsignedBigInteger('plot_id');
            $table->foreign('plot_id')->references('id')->on('plots');
            $table->bigInteger('commission')->nullable();
            $table->string('director')->nullable();
            $table->string('director_commission')->nullable();
            $table->string('assistant_director')->nullable();
            $table->string('assistant_director_commission')->nullable();
            $table->string('crm')->nullable();
            $table->string('crm_commission')->nullable();
            $table->string('senior_director')->nullable();
            $table->string('senior_director_commission')->nullable();
            $table->string('chief_director')->nullable();
            $table->string('chief_director_commission')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketer_payouts');
    }
};
