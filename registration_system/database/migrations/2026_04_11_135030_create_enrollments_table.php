<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollmentsTable extends Migration
{
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['enrolled', 'dropped', 'completed', 'pending'])->default('enrolled');
            $table->date('enrollment_date')->default(now());
            $table->timestamps();
            
            $table->unique(['student_id', 'section_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('enrollments');
    }
}