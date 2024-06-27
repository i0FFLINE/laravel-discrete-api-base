<?php

namespace IOF\DiscreteApi\Base\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use IOF\DiscreteApi\Base\Traits\BelongsToUser;

/**
 * @property string $user_id
 * @property string $old_email_verified_at
 * @property string $new_email_verified_at
 * @property DateTimeInterface $valid_until
 */
class UserEmailChange extends Model
{
    use BelongsToUser;

    protected $table = 'user_email_changes';
    protected $fillable = [
        'user_id',
        'old_email',
        'new_email',
        'old_email_verified_at',
        'new_email_verified_at',
        'valid_until',
    ];
    protected $hidden = [
        'id',
    ];
    protected $casts = [
        'old_email_verified_at' => 'datetime',
        'new_email_verified_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    protected $touches = ['user'];
    protected $appends = [
        'is_old_confirmed',
        'is_new_confirmed',
        'is_valid',
    ];

    public function getIncrementing(): bool
    {
        return true;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    public function isOldConfirmed(): Attribute
    {
        return Attribute::get(fn (): bool => (bool)$this->old_email_verified_at);
    }

    public function isNewConfirmed(): Attribute
    {
        return Attribute::get(function (): bool {
            return (bool)$this->new_email_verified_at;
        });
    }

    public function isValid(): Attribute
    {
        return Attribute::get(function (): bool {
            return Carbon::parse($this->valid_until)->greaterThanOrEqualTo(now());
        });
    }
}
