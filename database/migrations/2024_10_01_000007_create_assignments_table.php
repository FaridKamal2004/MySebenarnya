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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('Inquiry_Id');
            $table->unsignedBigInteger('Agency_Id');
            $table->unsignedBigInteger('Assigned_by');
            $table->timestamp('Assigned_at');
            $table->timestamps();
            
            $table->foreign('Inquiry_Id')->references('InquiryId')->on('inquiries');
            $table->foreign('Agency_Id')->references('id')->on('agency_users');
            $table->foreign('Assigned_by')->references('id')->on('mcmc_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};