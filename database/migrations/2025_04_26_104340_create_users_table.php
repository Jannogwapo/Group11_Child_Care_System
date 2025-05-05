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
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('access_id');
            $table->unsignedBigInteger('gender_id');
            $table->rememberToken();
            $table->timestamps();

            // Add foreign key constraints
            $table->foreign('role_id')
                  ->references('id')
                  ->on('user_role')
                  ->onDelete('cascade');

            $table->foreign('gender_id')
                  ->references('id')
                  ->on('gender')
                  ->onDelete('cascade');
            $table->foreign('access_id')
                  ->references('id')
                  ->on('access_logs')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        
        Schema::dropIfExists('users');
    }
}; 