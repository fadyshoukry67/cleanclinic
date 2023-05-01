<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class anatomy extends Model
{
    use HasFactory;
    protected $fillable = [
        'User-id',
        'anatomy',
    ];
    public $timestamps = false;
}
