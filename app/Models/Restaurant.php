<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens; // Add this line
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes; // Use the trait here

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    public function foodCategories()
{
    return $this->hasMany(FoodCategory::class);
}


}
