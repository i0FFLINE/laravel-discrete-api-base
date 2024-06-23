<?php

namespace IOF\DiscreteApi\Base\Models;

use App\Models\User;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use IOF\DiscreteApi\Base\Traits\BelongsToOrganization;
use IOF\DiscreteApi\Base\Traits\BelongsToUser;
use Ramsey\Collection\Collection;

/**
 * @property string $id
 * @property string $organization_id
 * @property string $user_id
 * @property string $role
 * @property string $invite_role
 * @property string $invited_by
 * @property string $invite_confirmed_by
 * @property string $updated_by
 * @property DateTimeInterface $invited_at
 * @property DateTimeInterface $invite_confirmed_at
 * @property DateTimeInterface $created_at
 * @property DateTimeInterface $updated_at
 * @property Collection $workspaces
 * @property Organization $organization
 * @property User $user
 * @property User $invitedBy
 * @property User $inviteConfirmedBy
 * @property User $updatedBy
 * @method static where(string $key, mixed $val)
 * @method static whereIn(string $string, mixed $pluck)
 */
class OrganizationMember extends Model
{
    use SoftDeletes;
    use BelongsToOrganization;
    use BelongsToUser;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'organizations_members';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'organization_id',
        'user_id',
        'role',
        'invite_role',
        'invited_by',
        'invite_confirmed_by',
        'updated_by',
        'invited_at',
        'invite_confirmed_at',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'invite_role',
        'invited_by',
        'invite_confirmed_by',
        'updated_by',
        'invited_at',
        'invite_confirmed_at',
        'created_at',
        'updated_at',
        'deleted_at',
        'pivot',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'invited_at' => 'datetime',
        'invite_confirmed_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [];

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    public function getIncrementing(): bool
    {
        return true;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function inviteConfirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invite_confirmed_by');
    }

}
