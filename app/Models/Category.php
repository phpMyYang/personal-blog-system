<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Para sa paggawa ng slug

class Category extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'slug'];

    /**
     * Awtomatikong gawing slug ang 'name' kapag nagse-save.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    /**
     * Kunin ang lahat ng posts na nabibilang sa category na ito.
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'category_post');
    }
}