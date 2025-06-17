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
        Schema::create('mcmc_users', function (Blueprint $table) {
            $table->id();
            $table->string('MCMCUserName');
            $table->string('MCMCEmail')->unique();
            $table->string('MCMCPassword');
            $table->string('password');
            $table->string('MCMCContact');
            $table->unsignedBigInteger('RoleID');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            
            $table->foreign('RoleID')->references('RoleID')->on('role_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcmc_users');
    }
};