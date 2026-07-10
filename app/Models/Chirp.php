<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chirp extends Model
{
    protected $fillable = [
        'user_id',
        'message',
        'parent_id',
    ];
    public function user()
    {
        return $this->belongsTo((User::class));
    }

    public function parent()
    {
        return $this->belongsTo(Chirp::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Chirp::class, 'parent_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likesCount(): int
    {
        return $this->likes()->count();
    }

    public function isLikedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
