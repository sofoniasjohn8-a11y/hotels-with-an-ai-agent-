<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_reference')->unique();
            $table->foreignId('guest_id')->constrained()->restrictOnDelete();
            $table->foreignId('room_type_id')->constrained()->restrictOnDelete();
            $table->foreignId('room_id')->nullable()->constrained()->nullOnDelete();
            $table->date('check_in');
            $table->date('check_out');
            $table->unsignedTinyInteger('guest_count')->default(1);
            $table->decimal('nightly_rate', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('status')->default('confirmed')->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['check_in', 'check_out']);
            $table->index(['status', 'check_in', 'check_out']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
