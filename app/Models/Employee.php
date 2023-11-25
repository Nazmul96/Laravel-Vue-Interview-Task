<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public function achievement()
    {
        return $this->belongsToMany(Achievement::class)->withPivot('achievement_date');
    }
}
