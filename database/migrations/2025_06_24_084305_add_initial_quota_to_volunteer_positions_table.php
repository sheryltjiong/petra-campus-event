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
        Schema::table('volunteer_positions', function (Blueprint $table) {
             $table->unsignedInteger('initial_quota')->nullable()->after('quota');
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('volunteer_positions', function (Blueprint $table) {
            //
            $table->dropColumn('initial_quota');
        });
    }
};
