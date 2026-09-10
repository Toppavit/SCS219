<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightLog extends Model
{
    use HasFactory;

    protected $fillable = ['weight', 'recorded_at', 'note'];

    protected $casts = [
        'recorded_at' => 'date',
    ];
}
