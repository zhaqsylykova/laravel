<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($city) {
            $city->users()->delete();
        });
    }


    public function users()
    {
        return $this->hasMany(User::class, 'city_id');
    }

    protected $fillable = [
        'city',
    ];
}
