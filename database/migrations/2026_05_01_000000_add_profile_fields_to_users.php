<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('age')->nullable()->after('password');
            $table->json('dietary_restrictions')->nullable()->after('age');
            $table->json('health_conditions')->nullable()->after('dietary_restrictions');
            $table->boolean('use_profile_suggestion')->default(true)->after('health_conditions');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['age', 'dietary_restrictions', 'health_conditions', 'use_profile_suggestion']);
        });
    }
};
