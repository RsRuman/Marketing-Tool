<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Moveon\Platform\Models\Platform;
use Moveon\Segmentation\Models\Filter;
use Moveon\Segmentation\Models\UserSegmentation;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'origin_id',
        'first_name',
        'last_name',
        'user_name',
        'email',
        'password',
        'token',
        'status',
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

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    const STATUS = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
    ];

    public function users(): HasMany
    {
        return $this->hasMany(self::class, 'origin_id');
    }

    public function origin(): BelongsTo
    {
        return $this->belongsTo(self::class, 'origin_id');
    }

    public function platform(): HasOne
    {
        return $this->hasOne(Platform::class, 'user_id', 'id');
    }

    # Filters
    public function filters(): HasMany
    {
        return $this->hasMany(Filter::class, 'user_id', 'id');
    }

    # User Segmentations
    public function userSegmentations(): HasMany
    {
        return $this->hasMany(UserSegmentation::class, 'user_id', 'id');
    }
}
