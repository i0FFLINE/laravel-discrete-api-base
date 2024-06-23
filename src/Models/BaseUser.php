<?php

namespace IOF\DiscreteApi\Base\Models;

use DateTimeInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use IOF\DiscreteApi\Base\Traits\HasEmailChanges;
use IOF\DiscreteApi\Base\Traits\HasNotificationAlerts;
use IOF\DiscreteApi\Base\Traits\HasOrganizations;
use IOF\DiscreteApi\Base\Traits\HasProfile;
use IOF\DiscreteApi\Base\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $public_name
 * @property string $password
 * @property boolean $is_banned
 * @property string $remember_token
 * @property DateTimeInterface $email_verified_at
 * @property DateTimeInterface $created_at
 * @property DateTimeInterface $updated_at
 * @property DateTimeInterface $deleted_at
 * @property boolean $is_confirmed
 * @property Profile $profile
 * @property Collection $roles
 * @property Collection $notification_alerts
 * @property Collection $emailChanges
 * @property Collection $organizations
 * @property Collection $membership
 * @method static where(string $key, mixed $val)
 */
class BaseUser extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasApiTokens;
    use SoftDeletes;
    use HasProfile;
    use HasRoles;
    use HasNotificationAlerts;
    use HasEmailChanges;
    use HasOrganizations;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'public_name',
        'password',
        'is_banned',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'email',
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
        'pivot',
        'roles',
    ];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'is_confirmed',
    ];

    public function getIncrementing(): bool
    {
        return true;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    public function isConfirmed(): Attribute
    {
        return Attribute::get(fn() => (bool)$this->hasVerifiedEmail());
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'deleted_at' => 'datetime',
        ];
    }
}
