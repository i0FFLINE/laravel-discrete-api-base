<?php

namespace IOF\DiscreteApi\Base\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use IOF\DiscreteApi\Base\Traits\BelongsToOrganization;
use IOF\DiscreteApi\Base\Traits\BelongsToUser;

/**
 * @property int $role
 * @property string $user_id
 * @property string $updated_by
 * @property Organization $organization
 * @method static whereIn(string $string, mixed $pluck)
 */
class OrganizationMember extends Model
{
    use SoftDeletes;
    use BelongsToOrganization;
    use BelongsToUser;

    public $incrementing = false;
    protected $table = 'organizations_members';
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
    protected $casts = [
        'invited_at' => 'datetime',
        'invite_confirmed_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    protected $appends = [];
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
