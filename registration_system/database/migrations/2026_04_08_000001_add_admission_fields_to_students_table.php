<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->enum('admission_status', ['pending', 'admitted', 'rejected'])->default('pending')->after('year_level');
            $table->date('admission_date')->nullable()->after('admission_status');
            $table->string('admission_reference')->nullable()->unique()->after('admission_date');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['admission_status', 'admission_date', 'admission_reference']);
        });
    }
};
