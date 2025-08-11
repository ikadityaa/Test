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
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('qr_session_id')->constrained()->onDelete('cascade');
            $table->timestamp('check_in_time');
            $table->string('check_in_method')->default('qr'); // qr, manual
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'qr_session_id']); // Prevent duplicate check-ins
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
