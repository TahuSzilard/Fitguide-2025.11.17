<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    public function product()
    {
        return $this->belongsTo(ProductType::class);
    }
}
