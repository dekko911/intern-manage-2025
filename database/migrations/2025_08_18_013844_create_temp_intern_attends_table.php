<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('temp_intern_attends', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('intern_attend_id')->references('id')->on('intern_attends')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignUuid('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('status', ['Hadir', 'Ijin', 'Sakit', 'Alpa']);
            $table->date('tanggal');
            $table->time('jam_masuk');
            $table->time('jam_keluar');
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_intern_attends');
    }
};
