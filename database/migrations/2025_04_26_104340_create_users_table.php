<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('role_id')->references('id')->on('user_role')->onDelete('cascade');
            $table->foreignId('access_id')->references('id')->on('access_logs')->onDelete('cascade');
            $table->foreignId('gender_id')->references('id')->on('gender')->onDelete('cascade');
            $table->rememberToken();
            $table->timestamps();

        
        });
    }

    public function down(): void
    {
        
        Schema::dropIfExists('users');
    }
}; 