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
            $table->date('date');
            $table->string('time');
            $table->string('location');
            $table->text('description');
            $table->integer('slot_peserta');
            $table->integer('skkk_points')->default(0);
            $table->string('skkk_category')->nullable();
            $table->string('registration_phase')->default('volunteer_open');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};