<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('course_code', 20);
            $table->string('course_name', 150);
            $table->unsignedTinyInteger('credit_hours');
            $table->string('semester', 30);

            $table->timestamps();

            $table->unique(['user_id', 'course_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
