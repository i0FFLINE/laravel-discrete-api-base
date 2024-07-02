<?php

namespace IOF\DiscreteApi\Base\Models;

use DateTimeInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use IOF\DiscreteApi\Base\Traits\HasEmailChanges;
use IOF\DiscreteApi\Base\Traits\HasNotificationAlerts;
use IOF\DiscreteApi\Base\Traits\HasOrganizations;
use IOF\DiscreteApi\Base\Traits\HasProfile;
use IOF\DiscreteApi\Base\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;

/**
 * @property string $id
 * @property string $email
 * @property DateTimeInterface $email_verified_at
 * @property string $name
 * @property string $public_name
 * @property string $password
 * @property boolean $two_factor_enabled
 * @property string $two_factor_secret
 * @property Profile $profile
 * @property Collection $roles
 * @method static create(array $array)
 * @method static where($column, $operator = null, $value = null, $boolean = 'and')
 * @method bool hasRole(array|string|int $role)
 * @method Model|null assignRole(string $role)
 * @method Collection roles()
 * @see Builder
 * @see HasRoles
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

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'email',
        'public_name',
        'password',
        'two_factor_enabled',
        'two_factor_secret',
        'is_banned',
        'is_elevated',
    ];
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
        'pivot',
        'roles',
        'two_factor_secret',
    ];
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
        return Attribute::get(fn() => $this->hasVerifiedEmail());
    }

    public function createToken(string $name, array $abilities = ['*'], ?DateTimeInterface $expiresAt = null): NewAccessToken
    {
        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken = Str::random(41)),
            'abilities' => $abilities,
        ]);

        return new NewAccessToken($token, $token->getKey() . '|' . $plainTextToken);
    }

    protected function casts(): array
    {
        return [
            'two_factor_enabled' => 'boolean',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'deleted_at' => 'datetime',
            'is_banned' => 'boolean',
            'is_elevated' => 'boolean',
        ];
    }
}
