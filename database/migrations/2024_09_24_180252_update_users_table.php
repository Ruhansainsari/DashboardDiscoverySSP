<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rename 'name' to 'first_name'
            $table->renameColumn('name', 'first_name');
            // Add new columns
            $table->string('last_name')->after('first_name');
            $table->string('city')->after('last_name');
            $table->string('password_confirmation')->nullable()->after('password'); // Add password confirmation
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverse the changes
            $table->renameColumn('first_name', 'name'); // Rename back to 'name'
            $table->dropColumn(['last_name', 'city', 'password_confirmation']); // Drop new columns
        });
    }
}

