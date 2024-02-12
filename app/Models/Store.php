<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Store extends Authenticatable

{
    use HasFactory,HasApiTokens, Notifiable;

    protected $table = 'stores';
    protected $fillable = ['id','name', 'description', 'phone', 'photo', 'city', 'password',
        'password_reset_code',];

   /* public function products()
    {
        return $this->hasMany(Product::class);
    }
*/
    public function markPhoneAsVerified()
    {
        $this->phone_verified_at = now();
        $this->confirmation_code = null;
        $this->save();
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function orders()
    {
        return $this->hasMany(
            Order::class, 'store_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'store_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }


}
