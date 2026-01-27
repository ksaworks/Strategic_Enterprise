<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Document extends Model
{
    protected $fillable = [
        'title',
        'file_path',
        'file_type',
        'file_size',
        'description',
        'documentable_type',
        'documentable_id',
    ];

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }
}
