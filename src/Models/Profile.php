<?php

namespace IOF\DiscreteApi\Base\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use IOF\DiscreteApi\Base\Helpers\DiscreteApiFilesystem;
use IOF\DiscreteApi\Base\Traits\BelongsToOrganization;
use IOF\DiscreteApi\Base\Traits\BelongsToUser;

/**
 * @property string $user_id
 * @property string $firstname
 * @property string $lastname
 * @property string $avatar_path
 * @property string $locale
 * @property string $organization_id
 * @property string $group_id
 * @property string $team_id
 * @property string $project_id
 */
class Profile extends Model
{
    use BelongsToUser;
    use BelongsToOrganization;

    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'profiles';
    protected $fillable = [];
    protected $hidden = ['id', 'user_id'];
    protected $casts = [];
    protected $appends = ['avatar_url'];
    protected $touches = ['user'];

    public function getFillable(): array
    {
        return [
            'user_id',
            'locale',
            'firstname',
            'lastname',
            'avatar_path',
            config('discreteapibase.organization.singular_name') . '_id',
        ];
    }

    public function getIncrementing(): bool
    {
        return true;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    public function avatarUrl(): ?Attribute
    {
        if (is_null($this->avatar_path)) {
            return null;
        }
        return Attribute::get(function (): ?string {
            return DiscreteApiFilesystem::get_file_url($this, 'avatar_path');
        });
    }

    public function deleteAvatar(): void
    {
        if (is_null($this->avatar_path)) {
            return;
        }
        Storage::disk(DiscreteApiFilesystem::check_disk($this))->delete($this->avatar_path);
        $this->forceFill([
            'avatar_path' => null,
        ])->save();
    }

    public function defaultAvatarUrl(): string
    {
        $name = trim(
            collect(explode(' ', $this->firstname . ' ' . $this->lastname))->map(function ($segment) {
                return mb_substr($segment, 0, 1);
            })->join(' ')
        );
        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=7F9CF5&background=EBF4FF';
    }
}
