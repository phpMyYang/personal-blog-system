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
        Schema::create('posts', function (Blueprint $table) {
            // Base sa plano: dapat UUID ang identifier 
            $table->uuid('id')->primary(); 

            // Foreign key para malaman kung sino ang user na may-ari ng post
            // Gagamit tayo ng foreignUuid para mag-match sa UUID ng 'users' table
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');

            $table->string('title'); 
            $table->text('content'); 

            // [cite: 106]
            $table->string('featured_image')->nullable(); 

            // Para sa Published/Draft status 
            $table->boolean('is_published')->default(false); 

            // Ito ang magha-handle ng "Creation date" 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
