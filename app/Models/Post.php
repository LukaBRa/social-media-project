<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'image_name'
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function getUser(){
        return $this->user();
    }

    public function likes(): BelongsToMany{
        return $this->belongsToMany(User::class, 'likes', 'post_id', 'user_id');
    }

    public function numberOfLikes(){
        return $this->likes()->count();
    }

    public function comments(): HasMany{
        return $this->hasMany(Comment::class);
    }

    public function numberOfComments(){
        return $this->comments()->count();
    }

    public function usersSaved(): BelongsToMany{
        return $this->belongsToMany(Saved::class);
    }
}
