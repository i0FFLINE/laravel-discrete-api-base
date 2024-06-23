<?php

namespace IOF\DiscreteApi\Base\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use IOF\DiscreteApi\Base\Traits\BelongsToUser;

/**
 * @property string $id
 * @property string $user_id
 * @property string $type
 * @property boolean $dismissable
 * @property integer $auto_dismiss
 * @property string $message
 * @property DateTimeInterface $read_at
 * @property DateTimeInterface $created_at
 * @property DateTimeInterface $updated_at
 * @property DateTimeInterface $deleted_at
 * @method static where(string $key, mixed $val)
 * @method static findOrFail(string $value)
 * @method static find($value)
 */
class NotificationAlerts extends Model
{
    use BelongsToUser;

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
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notification_alerts';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',
        'dismissable',
        'auto_dismiss',
        'message',
        'read_at',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'read_at',
        'user_id',
        'created_at',
        'updated_at',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'read_at' => 'datetime',
    ];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'is_read',
        'notification_created'
    ];

    public function isRead(): Attribute
    {
        return new Attribute(
            get: fn (): ?bool => (bool)$this->read_at
        );
    }

    public function notificationCreated(): Attributew
    {
        return new Attribute(
            get: fn (): ?string => $this->created_at
        );
    }

    public function getIncrementing(): bool
    {
        return true;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

}
