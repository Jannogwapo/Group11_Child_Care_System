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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');            
            $table->string('incident_type');
            $table->text('incident_description')->nullable();
            $table->string('incident_location')->nullable();
            $table->date('incident_date');
            $table->string('incident_image')->nullable();
            $table->timestamps();

            // NOTE: If the incidents table already exists, create a new migration to drop the client_id column.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
