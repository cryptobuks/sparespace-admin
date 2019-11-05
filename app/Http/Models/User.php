<?php

namespace App\Http\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	public function request(){
		return $this->hasMany('App\Http\Models\Reques','USER_ID','ID');
	}

	public function request_detail() {
		return $this->hasMany('App\Http\Models\RequestDetail','USER_ID','ID');	
	}

	public function coupon_list() {
		return $this->hasMany('App\Http\Models\CouponUser' , 'USER_ID' , 'ID');
	}
}
