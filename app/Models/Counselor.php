<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Counselor extends Model
{
    protected $fillable = [
        'name',
        'pangkat',
        'nrp',
        'jabatan',
        'kesatuan',
        'telegram',
        'religion',
        'emoji',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
