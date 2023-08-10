<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\GenderStatus;
use App\Enums\MilitaryStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id', 'updated_at', 'created_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'gender' => GenderStatus::class,
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birthday' => 'date',
        'military_status' => MilitaryStatus::class,
    ];

    /**
     * Interact with the user's password.
     */
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn($value) => Hash::needsRehash($value) ? Hash::make($value) : $value,
        );
    }

    /**
     * Interact with the user's password.
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->first_name . " " . $this->last_name,
        );
    }

    /**
     * Get the categories for the category.
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'creator_id');
    }

    /**
     * Get the posts for the category.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }
}