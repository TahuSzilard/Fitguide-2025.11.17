<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Muscle extends Model
{
    protected $fillable = ['name', 'slug'];

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'exercise_muscle');
    }
}
