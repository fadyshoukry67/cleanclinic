<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class usersImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'User-id',
        'profile_image',
    ];
    public $timestamps = false;
}
