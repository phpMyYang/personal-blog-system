<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory, HasUuids; 

    /**
     * Ang mga attributes na pwedeng i-mass assign.
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'featured_image',
        'is_published',
    ];

    /**
     * Kunin ang user na nagmamay-ari ng post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Kunin ang lahat ng comments para sa post, pinakabago muna.
     */
    public function comments() {
        // Kunin lang ang mga VERIFIED comments
        return $this->hasMany(Comment::class)->where('is_verified', true)->latest();
    }

    /**
     * Kunin ang lahat ng categories na kinabibilangan ng post na ito.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_post');
    }
}
