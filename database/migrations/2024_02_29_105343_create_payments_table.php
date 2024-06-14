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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->nullable();
            $table->string('father_or_husband_name')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('whatsapp_no')->nullable();
            $table->string('address')->nullable();
            $table->string('reference_id')->nullable();
            $table->index('project_id');
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->index('plot_id');
            $table->unsignedBigInteger('plot_id');
            $table->foreign('plot_id')->references('id')->on('plots');
            $table->index('marketer_id');
            $table->unsignedBigInteger('marketer_id');
            $table->foreign('marketer_id')->references('id')->on('marketers');
            $table->date('payment_date')->nullable();
            $table->tinyInteger('payment_status')->default(1);
            $table->string('payment_details')->nullable();
            $table->string('particulars')->nullable();
            $table->decimal('amount_paid', 12, 2)->nullable();
            $table->string('amount_in_words')->nullable();
            $table->enum('payment_type', ['Cash','IMPS','Cheque','NEFT','RTGS','Card'])->default('Cash')->nullable();
            $table->string('cheque_number')->nullable();
            $table->date('cheque_date')->nullable();
            $table->string('bank')->nullable();
            $table->string('branch')->nullable();
            $table->string('ref_no')->nullable();
            $table->timestamp('initial_updated_at')->nullable();
            $table->timestamp('partial_updated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
