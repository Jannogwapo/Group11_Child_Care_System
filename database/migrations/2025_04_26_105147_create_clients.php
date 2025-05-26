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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('clientLastName');
            $table->string('clientFirstName');
            $table->string('clientMiddleName')->nullable();
            $table->date('clientBirthdate');
            $table->integer('clientAge'); // Corrected
            $table->foreignId('clientgender')->references('id')->on('gender')->onDelete('cascade');
            $table->string('clientaddress');
            $table->string('clientguardian');
            $table->string('clientguardianrelationship');
            $table->string('guardianphonenumber'); // Changed from integer to string
            $table->foreignId('case_id')->references('id')->on('case')->onDelete('cascade');
            $table->date('clientdateofadmission');
            $table->foreignId('status_id')->references('id')->on('status')->onDelete('cascade'); // Corrected from `id` to `unsignedBigInteger`// Corrected
            $table->foreignId('isAStudent')->references('id')->on('isAStudent')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->references('id')->on('branch')->onDelete('cascade');
            $table->foreignId('isAPwd')->references('id')->on('isAPwd')->onDelete('cascade');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('location_id')->references('id')->on('location')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      
        Schema::dropIfExists('clients');
    }
};