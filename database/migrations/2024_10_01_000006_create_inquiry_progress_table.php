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
        Schema::create('inquiry_progress', function (Blueprint $table) {
            $table->id('ProgressID');
            $table->string('ProgressResult')->nullable();
            $table->text('ProgressDescription');
            $table->text('ProgressEvidence')->nullable();
            $table->text('ProgressReferences')->nullable();
            $table->unsignedBigInteger('AgencyID')->nullable();
            $table->unsignedBigInteger('InquiryID');
            $table->unsignedBigInteger('MCMCID')->nullable();
            $table->timestamps();
            
            $table->foreign('AgencyID')->references('id')->on('agency_users');
            $table->foreign('InquiryID')->references('InquiryId')->on('inquiries');
            $table->foreign('MCMCID')->references('id')->on('mcmc_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiry_progress');
    }
};