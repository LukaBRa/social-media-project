<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image_path',
        'user_folder',
        'user_type'
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function posts(): HasMany{
        return $this->hasMany(Post::class);
    }

    public function followers(){
        return $this->belongsToMany(User::class, 'followers', 'followed_id', 'follower_id');
    }

    public function followed(){
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'followed_id');
    }

    public function likedPosts(): BelongsToMany{
        return $this->belongsToMany(Post::class, 'likes', 'user_id', 'post_id');
    }

    public function likePost(String $postId){
        $this->likedPosts()->attach($postId);
    }

    public function dislikePost(String $postId){
        $this->likedPosts()->detach($postId);
    }

    public function liked(String $postId){
        return $this->likedPosts->contains($postId);
    }

    public function commented(): HasMany{
        return $this->hasMany(Comment::class);
    }

    public function savedPosts(): HasMany{
        return $this->hasMany(Saved::class);
    }

    public function savePost(String $postId){
        $this->savedPosts()->attach($postId);
    }

    public function unsavePost(String $postId){
        $this->savedPosts()->detach($postId);
    }

    public function notifications(): HasMany{
        return $this->hasMany(Notification::class);
    }

}
