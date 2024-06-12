<?php

namespace IOF\DiscreteApi\Base\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use IOF\DiscreteApi\Base\Traits\BelongsToUser;

class UserEmailChange extends Model
{
    use BelongsToUser;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_email_changes';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'old_email',
        'new_email',
        'old_email_verified_at',
        'new_email_verified_at',
        'valid_until',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'old_email_verified_at' => 'datetime',
        'new_email_verified_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    /**
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = ['user'];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
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
        return Attribute::get(function (): bool {
            return (bool) $this->old_email_verified_at;
        });
    }

    public function isNewConfirmed(): Attribute
    {
        return Attribute::get(function (): bool {
            return (bool) $this->new_email_verified_at;
        });
    }

    public function isValid(): Attribute
    {
        return Attribute::get(function (): bool {
            return Carbon::parse($this->valid_until)->greaterThanOrEqualTo(now());
        });
    }
}
