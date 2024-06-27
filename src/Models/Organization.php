<?php

namespace IOF\DiscreteApi\Base\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use IOF\Utils\Sorter;

/**
 * @property string $id
 * @property string $owner_id
 * @property Collection $membership
 * @method static findOrFail(string $id)
 * @method static create(array $array)
 */
class Organization extends Model
{
    use SoftDeletes;

    public $incrementing = false;
    protected $table = 'organizations';
    protected $fillable = [
        'owner_id',
        Sorter::FIELD,
        'title',
        'description',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'pivot',
    ];
    protected $casts = [
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

    public function role(): ?string
    {
        if (isset($this->pivot)) {
            return config('discreteapiorganizations.roles')[$this->pivot->role];
        }
        return null;
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'organizations_members',
            'organization_id',
            'user_id'
        )->withPivot(['role']);
    }

    public function membership(): HasMany
    {
        return $this->hasMany(OrganizationMember::class, 'organization_id');
    }
}
