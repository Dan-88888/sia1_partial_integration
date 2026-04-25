<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_applications', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('course');
            $table->string('birth_certificate_path')->nullable()->after('photo_path');
            $table->string('report_card_path')->nullable()->after('birth_certificate_path');
        });
    }

    public function down(): void
    {
        Schema::table('student_applications', function (Blueprint $table) {
            $table->dropColumn(['photo_path', 'birth_certificate_path', 'report_card_path']);
        });
    }
};
