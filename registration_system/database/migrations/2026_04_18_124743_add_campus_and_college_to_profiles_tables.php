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
        Schema::table('students', function (Blueprint $table) {
            $table->string('campus')->nullable()->after('student_number');
            $table->string('college')->nullable()->after('course');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->string('campus')->nullable()->after('teacher_id');
            $table->string('college')->nullable()->after('campus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['campus', 'college']);
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn(['campus', 'college']);
        });
    }
};
