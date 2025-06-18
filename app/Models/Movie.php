<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use app\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Movie extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'release_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'release_date' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the movie.
     *
     * @return BelongsToMany<User>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_movies')
            ->withPivot('status')
            ->withTimestamps();
    }
}
