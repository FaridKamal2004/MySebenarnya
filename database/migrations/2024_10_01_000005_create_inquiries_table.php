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
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id('InquiryId');
            $table->string('Title');
            $table->text('Description');
            $table->string('sourceURL')->nullable();
            $table->string('attachment')->nullable();
            $table->unsignedBigInteger('submitted_by');
            $table->unsignedBigInteger('agency_id')->nullable();
            $table->enum('status', ['pending', 'assigned', 'in_progress', 'completed', 'rejected'])->default('pending');
            $table->timestamp('submitted_at');
            $table->timestamps();
            
            $table->foreign('submitted_by')->references('id')->on('public_users');
            $table->foreign('agency_id')->references('id')->on('agency_users')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};