<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_type',
        'model_id',
        'uuid',
        'collection_name',
        'name',
        'file_name',
        'mime_type',
        'disk',
        'conversions_disk',
        'size',
        'manipulations',
        'custom_properties',
        'generated_conversions',
        'responsive_images',
        'order_column',
    ];

    protected $casts = [
        'manipulations' => 'array',
        'custom_properties' => 'array',
        'generated_conversions' => 'array',
        'responsive_images' => 'array',
    ];

    /**
     * Get the parent model.
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the URL for the media file.
     */
    public function getUrl(): string
    {
        return \Storage::disk($this->disk)->url($this->getFullPath());
    }

    /**
     * Get the full path of the file.
     */
    public function getFullPath(): string
    {
        return $this->collection_name . '/' . $this->file_name;
    }

    /**
     * Delete the file from storage.
     */
    public function deleteFile(): bool
    {
        return \Storage::disk($this->disk)->delete($this->getFullPath());
    }

    /**
     * Delete the media record and the file.
     */
    public function deleteWithFile(): bool
    {
        $this->deleteFile();
        return $this->delete();
    }
}
