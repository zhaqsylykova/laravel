<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
    'user_id',
        'store_id',
    'product',
    'quantity',
    'order_total',
    'status', 'store_id'
        ];
    protected $dates = ['order_time'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public  function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
