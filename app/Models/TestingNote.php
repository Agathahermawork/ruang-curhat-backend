<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestingNote extends Model
{
    protected $fillable = [
        'name',
        'message',
    ];
}
