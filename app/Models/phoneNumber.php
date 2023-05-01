<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class phoneNumber extends Model
{
    use HasFactory;
    protected $fillable = [
        'User-id',
        'phone_number',
    ];
    public $timestamps = false;
}

