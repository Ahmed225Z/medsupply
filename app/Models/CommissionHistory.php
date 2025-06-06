<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class CommissionHistory extends Model
{

    public function order() {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }
}
