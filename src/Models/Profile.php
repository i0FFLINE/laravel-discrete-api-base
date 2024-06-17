<?php

namespace IOF\DiscreteApi\Base\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use IOF\DiscreteApi\Base\Traits\BelongsToUser;

/**
 * @property string $user_id
 */
class Profile extends Model
{
    use BelongsToUser;

    public $timestamps = false;
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
    protected $table = 'profiles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'locale', 'firstname', 'lastname', 'avatar_path'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['id', 'user_id'];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['avatar_url'];
    /**
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = ['user'];

    public function getIncrementing(): bool
    {
        return true;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    public function avatarUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            return $this->avatar_path ? Storage::disk($this->avatarDisk())->url($this->avatar_path) : $this->defaultAvatarUrl();
        });
    }

    /**
     * Get the disk that profile avatars should be stored on.
     */
    public function avatarDisk(): string
    {
        return isset($_ENV['VAPOR_ARTIFACT_NAME']) ? 's3' : 'public';
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     */
    public function defaultAvatarUrl(): string
    {
        $name = trim(
            collect(explode(' ', $this->firstname.' '.$this->lastname))->map(function ($segment) {
                return mb_substr($segment, 0, 1);
            })->join(' ')
        );
        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Update the user's profile avatar.
     */
    public function updateProfileAvatar(UploadedFile $photo, string $storagePath = 'profile-avatars'): void
    {
        tap($this->avatar_path, function ($previous) use ($photo, $storagePath) {
            $this->forceFill([
                'avatar_path' => $photo->storePublicly($storagePath, ['disk' => $this->avatarDisk()]),
            ])->save();
            if ($previous) {
                Storage::disk($this->avatarDisk())->delete($previous);
            }
        });
    }

    /**
     * Delete the user's profile avatar.
     */
    public function deleteAvatar(): void
    {
        if (is_null($this->avatar_path)) {
            return;
        }
        Storage::disk($this->avatarDisk())->delete($this->avatar_path);
        $this->forceFill([
            'avatar_path' => null,
        ])->save();
    }

}
