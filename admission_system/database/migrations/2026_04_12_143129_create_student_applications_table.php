<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_applications', function (Blueprint $table) {
            $table->id();
            // Personal Information
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('name_extender')->nullable();
            $table->integer('age');
            $table->enum('sex', ['Male', 'Female']);
            $table->string('civil_status');
            $table->date('date_of_birth');
            $table->string('birth_place');
            $table->string('contact_number');
            $table->string('gmail_account')->unique();
            $table->text('temporary_address');
            $table->text('permanent_address');
            // Guardian Information
            $table->string('guardian_name');
            $table->string('guardian_relationship');
            $table->string('guardian_phone');
            // Academic Information
            $table->enum('student_type', ['Regular', 'Irregular', 'Transferee']);
            $table->enum('campus', ['Goa', 'San Jose', 'Lagonoy']);
            $table->string('college');
            $table->string('course');
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Waitlisted'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_applications');
    }
};