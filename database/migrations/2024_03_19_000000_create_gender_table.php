<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gender', function (Blueprint $table) {
            $table->id();
            $table->string('gender_name');
            $table->timestamps();
        });

        // Insert default gender options
        DB::table('gender')->insert([
            ['gender_name' => 'Male'],
            ['gender_name' => 'Female']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gender');
    }
}; 