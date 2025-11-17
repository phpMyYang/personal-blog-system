<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'post_id',
        'user_id',
        'guest_name',
        'guest_email',
        'content',
        'is_verified',        
        'verification_token', 
    ];

    /**
     * Kunin ang post na may-ari ng comment.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Kunin ang user na may-ari ng comment (kung meron).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}