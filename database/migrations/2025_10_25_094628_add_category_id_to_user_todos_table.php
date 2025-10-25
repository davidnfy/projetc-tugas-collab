<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_todos', function (Blueprint $table) {
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('user_todo_categories')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('user_todos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });
    }
};
