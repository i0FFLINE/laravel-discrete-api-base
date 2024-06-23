<?php

namespace IOF\DiscreteApi\Base\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use IOF\DiscreteApi\Base\Traits\BelongsToOrganization;
use IOF\Utils\Sorter;

/**
 * @property string $id
 * @property string $organization_id
 * @property integer sort_order
 * @property string $title
 * @property string $description
 * @property DateTimeInterface $created_at
 * @property DateTimeInterface $updated_at
 * @property DateTimeInterface $deleted_at
 * @property Organization $organization
 * @method static where(string $key, mixed $var)
 * @method static find(string $value)
 * @method static findOrFail(string $value)
 */
class Workspace extends Model
{
    use SoftDeletes;
    use BelongsToOrganization;

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
    protected $table = 'workspaces';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'organization_id',
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
}
