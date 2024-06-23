<?php

namespace IOF\DiscreteApi\Base\Models;

use App\Models\User;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use IOF\DiscreteApi\Base\Traits\HasWorkspace;
use IOF\Utils\Sorter;
use Ramsey\Collection\Collection;

/**
 * @property string $id
 * @property string $owner_id
 * @property integer $sort_order
 * @property string $title
 * @property string $description
 * @property DateTimeInterface $created_at
 * @property DateTimeInterface $updated_at
 * @property DateTimeInterface $deleted_at
 * @property Collection $workspaces
 * @property Collection $owner
 * @property Collection $members
 * @property Collection $membership
 * @method static where(string $key, mixed $val)
 * @method static create(array $array)
 * @method static find(string $value)
 * @method static findOrFail(string $value)
 */
class Organization extends Model
{
    use SoftDeletes;
    use HasWorkspace;

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
    protected $table = 'organizations';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'owner_id',
        Sorter::FIELD,
        'title',
        'description',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
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

    public function role(): ?string
    {
        if (isset($this->pivot)) {
            return config('discreteapiorganizations.roles')[$this->pivot->role];
        }
        return null;
    }

    public function workspaces(): HasMany
    {
        return $this->hasMany(Workspace::class, 'organization_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organizations_members', 'organization_id', 'user_id')->withPivot(['role']);
    }

    public function membership(): HasMany
    {
        return $this->hasMany(OrganizationMember::class, 'organization_id');
    }
}
