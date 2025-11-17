<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids; 
// use Illuminate\Contracts\Auth\MustVerifyEmail; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable 
{
    use HasFactory, Notifiable, HasUuids; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Kunin ang lahat ng posts na pagmamay-ari ng user.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Kunin ang lahat ng comments na ginawa ng user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}