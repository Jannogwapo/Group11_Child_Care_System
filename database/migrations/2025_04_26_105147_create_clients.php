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
            $table->unsignedBigInteger('clientgender');
            $table->string('clientaddress');
            $table->string('clientguardian');
            $table->string('clientguardianrelationship');
            $table->string('guardianphonenumber'); // Changed from integer to string
            $table->unsignedBigInteger('case_id'); // Corrected from `int` to `integer`
            $table->date('clientdateofadmission');
            $table->unsignedBigInteger('status_id'); // Corrected from `id` to `unsignedBigInteger`// Corrected
            $table->unsignedBigInteger('isAStudent'); // Corrected
            $table->unsignedBigInteger('branch_id') -> nullable();
            $table->unsignedBigInteger('isAPwd');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('location_id');
            $table->timestamps();
        });

        // Add foreign key constraints after table creation
        Schema::table('clients', function (Blueprint $table) {
            $table->foreign('clientgender')
                  ->references('id')
                  ->on('gender')
                  ->onDelete('cascade');

            $table->foreign('branch_id')
                  ->references('id')
                  ->on('branch')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('case_id')
                  ->references('id')
                  ->on('case')
                  ->onDelete('cascade');

            $table->foreign('status_id')
                  ->references('id')
                  ->on('status')
                  ->onDelete('cascade');

            $table->foreign('isAStudent')
                  ->references('id')
                  ->on('isAStudent')
                  ->onDelete('cascade');
                  
            $table->foreign('isAPwd')
                  ->references('id')
                  ->on('isAPwd')
                  ->onDelete('cascade');

            $table->foreign('location_id')
                  ->references('id')
                  ->on('location')
                  ->onDelete('cascade');

        


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