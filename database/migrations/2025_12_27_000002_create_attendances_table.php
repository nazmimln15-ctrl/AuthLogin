<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_session_id')->constrained('attendance_sessions');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('attended_at')->useCurrent();
            $table->string('qr_token')->nullable();
            $table->string('status')->default('present');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
