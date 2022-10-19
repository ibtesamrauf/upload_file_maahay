<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFileShareWith extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message'
    ];

    public function user_files(){
        return $this->hasMany(UserFile::class, 'user_file_share_with_id', 'id');
    }
}
