<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    protected $fillable = ['store', 'photo', 'caption'];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store', 'id');
    }
}
