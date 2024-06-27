<?php

namespace IOF\DiscreteApi\Base\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property string $name
 * @property string $is_protected
 * @property Collection $users
 * @method static get()
 * @method static where($column, $operator = null, $value = null, $boolean = 'and')
 */
class Role extends Model
{
    use SoftDeletes;

    protected $table = 'roles';
    protected $fillable = [
        'name',
        'label',
        'comment',
        'is_protected',
    ];
    protected $hidden = [
        'is_protected',
        'created_at',
        'updated_at',
        'deleted_at',
        'pivot',
    ];
    protected $casts = [
        'is_protected' => 'boolean',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
