<?php

namespace IOF\DiscreteApi\Base\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use IOF\DiscreteApi\Base\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property string $user_id
 * @property DateTimeInterface $read_at
 * @property DateTimeInterface $created_at
 * @method static where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static findOrFail(string $id)
 * @see Builder
 */
class NotificationAlerts extends Model
{
    use BelongsToUser;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'notification_alerts';
    protected $fillable = [
        'user_id',
        'type',
        'dismissable',
        'auto_dismiss',
        'message',
        'read_at',
    ];
    protected $hidden = [
        'read_at',
        'user_id',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'read_at' => 'datetime',
    ];
    protected $appends = [
        'is_read',
        'notification_created'
    ];

    public function isRead(): Attribute
    {
        return new Attribute(get: fn (): ?bool => (bool)$this->read_at);
    }

    public function notificationCreated(): Attribute
    {
        return new Attribute(get: fn (): ?string => $this->created_at);
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
