<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained()->restrictOnDelete();
            $table->string('room_number')->unique();
            $table->unsignedSmallInteger('floor')->nullable();
            $table->string('status')->default('available')->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['room_type_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
