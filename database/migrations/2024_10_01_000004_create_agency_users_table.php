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
        Schema::create('agency_users', function (Blueprint $table) {
            $table->id();
            $table->string('AgencyUserName');
            $table->string('AgencyEmail')->unique();
            $table->string('AgencyPassword');
            $table->string('password');
            $table->string('AgencyContact');
            $table->boolean('AgencyFirstLogin')->default(true);
            $table->unsignedBigInteger('RoleID');
            $table->unsignedBigInteger('MCMCID');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            
            $table->foreign('RoleID')->references('RoleID')->on('role_users');
            $table->foreign('MCMCID')->references('id')->on('mcmc_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_users');
    }
};