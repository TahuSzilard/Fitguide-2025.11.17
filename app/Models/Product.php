<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductType;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'description', 'price', 'image', 'product_type_id',
    ];

    public function getPriceFormattedAttribute(): string
    {
        return number_format($this->price, 2, '.', ' ') . ' Ft';
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return asset('images/placeholder-product.png');
        }
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        return asset('storage/products/'.$this->image);
    }
    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }
}