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
        Schema::create('category_post', function (Blueprint $table) {
            // Walang ID, dahil ang primary key ay ang kombinasyon ng dalawa
            $table->foreignUuid('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignUuid('post_id')->constrained('posts')->onDelete('cascade');

            $table->primary(['category_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_post');
    }
};
