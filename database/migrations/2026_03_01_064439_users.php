<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     *Execute by usign thhe command php artisan migrate . and use to add new coloum in the database table or modify the existing coloum in the database table
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // ✅ Add role column
            $table->string('role')->default('user');

            $table->rememberToken();
            $table->timestamps();
        });
    }


    //  This method is used to reverse the changes made by the up() method. In this case, it drops the 'role' column from the 'users' table if it exists. This allows you to roll back the migration if needed.

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
