<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    // app/Models/Product.php

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function usersInBasket()
    {
        return $this->belongsToMany(User::class, 'basket_user', 'product_id', 'user_id')->withTimestamps();
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites', 'product_id', 'user_id')->withTimestamps();
    }


    protected $fillable = ['name', 'description', 'price', 'category_id', 'subcategory_id', 'store_id', 'photo', 'total_quantity',
        'available_quantity'];

}
