<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('phone', 32)->nullable();
            $table->text('message');
            $table->string('status')->default('unread')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_messages');
    }
};
