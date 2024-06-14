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
        Schema::create('marketers', function (Blueprint $table) {
            $table->id();
            $table->string('marketer_vcity_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('father_name')->nullable();
            $table->string('qualification')->nullable();
            $table->date('date')->nullable();
            $table->date('dob')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('pincode')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('aadhar_no')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('acc_no')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('branch')->nullable();
            $table->string('director_vcity_id')->nullable();
            $table->string('director')->nullable();
            $table->string('ad_vcity_id')->nullable();
            $table->string('assistant_director')->nullable();
            $table->string('crm_vcity_id')->nullable();
            $table->string('crm')->nullable();
            $table->string('chief_vcity_id')->nullable();
            $table->string('chief_director')->nullable();
            $table->string('senior_vcity_id')->nullable();
            $table->string('senior_director')->nullable();
            $table->enum('designation', ['CC', 'CRM', 'CD', 'SD', 'Dir', 'AD'])->default('CC');
            $table->date('renewal_date')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketers');
    }
};
