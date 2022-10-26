<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'size',
        'folder_id',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo(Folder::class, 'folder_id');
        return $this->belongsTo(User::class, 'user_id');
    }
}
