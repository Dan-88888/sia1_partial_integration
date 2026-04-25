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
        Schema::table('enrollment_data', function (Blueprint $table) {
            $table->string('dept')->nullable();
            $table->string('tf_level')->nullable();
            $table->integer('late_enrollee_days')->default(0);
            $table->boolean('check_enrollment_count')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollment_data', function (Blueprint $table) {
            //
        });
    }
};
