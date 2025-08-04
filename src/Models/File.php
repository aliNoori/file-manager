<?php

namespace FileManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Model
{
    protected $fillable = [
        'path', 'original_name', 'mime_type', 'size', 'field', 'fileable_type', 'fileable_id'
    ];

    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }
}
