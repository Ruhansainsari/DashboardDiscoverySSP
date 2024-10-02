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
        Schema::table('ads', function (Blueprint $table) {
            // Drop the existing foreign key constraint on 'category_id'
            $table->dropForeign(['category_id']);

            // Recreate the foreign key with cascade on delete
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            // Drop the modified foreign key with cascade
            $table->dropForeign(['category_id']);

            // Recreate the original foreign key without cascade
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories');
        });
    }
};
