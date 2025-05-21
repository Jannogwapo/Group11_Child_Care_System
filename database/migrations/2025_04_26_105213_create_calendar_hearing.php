<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendar_hearings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('hearing_date');
            $table->string('time');
            $table->foreignId('branch_id')->references('id')->on('branch')->onDelete('cascade');
            $table->enum('status', ['scheduled', 'completed', 'postponed', 'cancelled', 'rescheduled']);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_hearings');
    }
}; 