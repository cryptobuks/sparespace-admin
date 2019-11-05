<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $table = 'payment_log';
    
    public function user(){
    	return $this->hasOne('App\Http\Models\User', 'ID', 'USER_ID');
    }
}
