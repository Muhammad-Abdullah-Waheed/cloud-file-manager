<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileVersion extends Model
{
    protected $fillable = [
        'file_id',
        'path',
        'size',
        'version_number',
        'user_id',
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
