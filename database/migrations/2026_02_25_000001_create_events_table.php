<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->enum('status', ['upcoming', 'active', 'completed', 'cancelled'])->default('upcoming');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('event_study_program', function (Blueprint $table) {
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('study_program_id')->constrained('study_programs')->cascadeOnDelete();
            $table->primary(['event_id', 'study_program_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_study_program');
        Schema::dropIfExists('events');
    }
};
