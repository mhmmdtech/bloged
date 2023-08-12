<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\GenderStatus;
use App\Enums\MilitaryStatus;
use App\Notifications\SendEmailVerificationNotification;
// use Illuminate\Auth\Listeners\SendEmailVerificationNotification as ListenersSendEmailVerificationNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    /**
     * Get the user that created the user.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get the users that created by user.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'creator_id');
    }

    /**
     * Send the customized email verification to registered user
     * 
     * https://laraveldaily.com/post/laravel-breeze-user-name-auth-email-templates
     * https://dev.to/frknasir/laravel-easily-customize-email-verification-url-58f9
     * https://techvblogs.com/blog/laravel-9-custom-email-verification-tutorial
     */
    public function sendEmailVerificationNotification(): void
    {
        $token = $this->verificationCodes->last()->token;
        $this->notify(new SendEmailVerificationNotification($token));
    }

    /**
     * Get the verification codes issue for the user.
     */
    public function verificationCodes(): HasMany
    {
        return $this->hasMany(UserVerificationCode::class);
    }

    /**
     * Get the provinces that created by user.
     */
    public function provinces(): HasMany
    {
        return $this->hasMany(Province::class, 'creator_id');
    }

    /**
     * Get the cities that created by user.
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'creator_id');
    }
}