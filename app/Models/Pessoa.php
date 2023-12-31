<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'stack' => 'array',
    ];
}
