<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class CouponUser extends Model
{
    protected $table = 'coupon_users';
    public $timestamps = null;

    public function user(){
    	return $this->hasOne('App\Http\Models\User', 'ID', 'USER_ID');
    }
    
    public function coupon(){
    	return $this->hasOne('App\Http\Models\Coupon','ID', 'COUPON_ID');
    }

    
}
