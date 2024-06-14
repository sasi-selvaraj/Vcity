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
        Schema::table('directors', function (Blueprint $table) {
            $table->index('marketer_id');
            $table->unsignedBigInteger('marketer_id');
            $table->foreign('marketer_id')->references('id')->on('marketers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('directors', function (Blueprint $table) {
            $table->dropColumn('marketer_id');
        });
    }
};
