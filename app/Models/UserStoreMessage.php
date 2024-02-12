<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStoreMessage extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'store_id', 'message_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
