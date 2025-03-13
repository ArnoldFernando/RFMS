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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('location');
            $table->text('description')->nullable();
            $table->text('file')->nullable();
            $table->string('civil_case_number')->nullable();
            $table->string('lot_number')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'deleted'])->default('pending');
            $table->foreignId('file_category_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
