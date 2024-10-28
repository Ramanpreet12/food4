<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoodCategory extends Model
{
    use HasFactory, SoftDeletes;

    // protected $fillable = [
    //     'name',
    //     'image',
    //     'status',
    //     'restaurant_id'
    // ];
    protected $guarded = ['id'];

    /**
     * Get the restaurant that owns the food category.
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Get the full image URL attribute.
     */
    // public function getImageUrlAttribute()
    // {
    //     return $this->image ? Storage::disk('public')->url($this->image) : null;
    // }
}
