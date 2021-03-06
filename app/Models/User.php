<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Permission;
use App\Models\CommentReply;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Role;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'name',
        'last_name',
        'email',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    #mutator for encrypt password
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function userHasRole($role_name)
    {
        foreach($this->roles as $role)
        {
            if(Str::lower($role_name) == Str::lower($role->name))
            {
                return true;
            }

            return false;
        }
    }

    public function getAvatarAttribute($value)
    {
        if(strpos($value, 'https://') !== FALSE || strpos($value, 'http://') !== FALSE)
            {
                return $value;
            }
        return asset('/images/avatar/' . $value);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function replies()
    {
        return $this->hasMany(CommentReply::class);
    }
}
