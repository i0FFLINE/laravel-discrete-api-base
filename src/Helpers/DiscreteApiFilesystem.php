<?php

namespace IOF\DiscreteApi\Base\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DiscreteApiFilesystem
{
    protected const GIGA = 1073741824; // (1024 * 1024 * 1024)
    protected const MEGA = 1048576; // (1024 * 1024)
    protected const KILO = 1024;

    public static function get_file(mixed $model, string $field_name): BinaryFileResponse|string|null
    {
        if ($model instanceof Model && !empty($model->{$field_name})) {
            return Storage::disk(static::check_disk($model))->url($model->{$field_name});
        } elseif (is_array($model) && !empty($model[$field_name])) {
            return Storage::disk(static::check_disk($model))->url($model[$field_name]);
        } else {
            return null;
        }
    }

    protected static function check_disk(mixed $model): string
    {
        if (method_exists($model, 'getImageDisk')) {
            return $model->getImageDisk();
        } elseif (method_exists($model, 'getAvatarDisk')) {
            return $model->getAvatarDisk();
        } else {
            return config('filesystems.default');
        }
    }

    public static function put_file(UploadedFile|string $file, string $fullPath, mixed $model, string $field_name): mixed
    {
        if ($file instanceof UploadedFile) {
            $storedPath = $file->store($fullPath, ['disk' => static::check_disk($model)]);
        } else {
            if (filter_var($file, FILTER_VALIDATE_URL)) {
                // is url
                // preventing to upload big files
                $fileInfo = static::get_remote_file_info($file);
                if ($fileInfo['exists'] && $fileInfo['size'] <= static::get_max_upload_size()) {
                    $storedPath = Storage::disk(static::check_disk($model))->putFile($fullPath, file_get_contents($file));
                } else {
                    $storedPath = false;
                }
            } else {
                // assume to local filesystem file
                // no max size limits
                $storedPath = Storage::disk(static::check_disk($model))->putFile($fullPath, file_get_contents($file));
            }
        }
        if ($storedPath) {
            if ($model instanceof Model) {
                static::remove_previous_file($model, $model->{$field_name});
                $model->forceFill([$field_name => $storedPath])->save();
            } elseif (is_array($model)) {
                static::remove_previous_file($model, $model[$field_name]);
                $model[$field_name] = $storedPath;
            } else {
                return null;
            }
            return $model;
        }
        return null;
    }

    protected static function get_remote_file_info($url): array
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $fileSize = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        $fileType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return [
            'exists' => (int)$httpResponseCode == 200,
            'size' => (int)$fileSize,
            'type' => $fileType,
        ];
    }

    protected static function get_max_upload_size(): int
    {
        return config('discreteapibase.filesystem.max_upload_size', 10) * static::MEGA;
    }

    protected static function remove_previous_file(mixed $model, string $fullPath = null): void
    {
        if (!empty($fullPath)) {
            Storage::disk(static::check_disk($model))->delete($fullPath);
        }
    }

    public static function get_file_url(mixed $model, string $field_name): ?string
    {
        if ($model instanceof Model && !empty($model->{$field_name})) {
            return Storage::disk(static::check_disk($model))->url($model->{$field_name});
        } elseif (is_array($model) && !empty($model[$field_name])) {
            return Storage::disk(static::check_disk($model))->url($model[$field_name]);
        }
        return null;
    }

    public static function del_file(mixed $model, string $field_name): mixed
    {
        if ($model instanceof Model && !empty($model->{$field_name})) {
            static::remove_previous_file($model, $model->{$field_name});
            $model->forceFill([$field_name => null])->save();
        } elseif (is_array($model) && !empty($model[$field_name])) {
            static::remove_previous_file($model, $model[$field_name]);
            $model[$field_name] = null;
        }
        return $model;
    }

    public static function get_file_path(mixed $model, string $field_name): ?string
    {
        if ($model instanceof Model && !empty($model->{$field_name})) {
            return Storage::disk(static::check_disk($model))->path($model->{$field_name});
        } elseif (is_array($model) && !empty($model[$field_name])) {
            return Storage::disk(static::check_disk($model))->path($model[$field_name]);
        }
        return null;
    }
}
