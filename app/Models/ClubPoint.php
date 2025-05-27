<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubPoint extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'Points', 'convert'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function club_point_details()
    {
        return $this->hasMany(ClubPointDetail::class);
    }
}
