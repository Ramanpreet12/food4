<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function foodCategory()
    {
        return $this->belongsTo(FoodCategory::class, 'category_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
