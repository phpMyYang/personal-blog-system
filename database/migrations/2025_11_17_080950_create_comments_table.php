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
        Schema::create('comments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Link sa Post
            $table->foreignUuid('post_id')->constrained('posts')->onDelete('cascade');

            // Link sa User (kung naka-log in)
            $table->foreignUuid('user_id')->nullable()->constrained('users')->onDelete('cascade');

            // Para sa Guests (kung HINDI naka-log in)
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();

            // Ang Komento
            $table->text('content');

            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
