<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    use HasFactory;
    protected $table = 'ranks';

    // تحديد الخصائص القابلة للكتابة
    protected $fillable = [
        'name', 'pts', 'max', 'min', 'image',
    ];
    
}
