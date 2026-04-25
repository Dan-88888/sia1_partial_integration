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
        Schema::table('courses', function (Blueprint $table) {
            $table->string('campus')->nullable()->after('department');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->string('campus')->nullable()->after('type');
            $table->string('college')->nullable()->after('campus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('campus');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['campus', 'college']);
        });
    }
};
