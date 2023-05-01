<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class xray extends Model
{
    use HasFactory;
    protected $fillable = [
        'User-id',
        'xray',
    ];
    public $timestamps = false;
}
