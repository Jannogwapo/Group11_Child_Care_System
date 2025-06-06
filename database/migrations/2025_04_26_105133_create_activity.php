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
        Schema::create('activity', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('activity_type');
            $table->string('activity_description');
            $table->string('activity_location');
            $table->date('activity_date');
            $table->string('activity_image')->nullable(); // Store image path or URL
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
        Schema::dropIfExists('activity');
    }
};