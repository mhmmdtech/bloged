<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    use HasFactory;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id', 'updated_at', 'created_at'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'old_model' => 'array',
        'new_model' => 'array',
    ];

    /**
     * Get the user that do the action.
     */
    public function actioner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actioner_id');
    }
}