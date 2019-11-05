<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class RequestDetail extends Model
{
    protected $primaryKey = 'ID';
    protected $table = 'request_detail';
    public $timestamps = null;
    protected $fillable = ['TRANS_NUMBER' , 'UPDTIME'];

    public function user(){
    	return $this->hasOne('App\Http\Models\User', 'ID', 'USER_ID');
    }
    
    public function request(){
    	return $this->hasOne('App\Http\Models\Reques','ID', 'REQUEST_ID');
    }

    public function goods(){
		return $this->hasMany('App\Http\Models\DetailGoods','BOXID','BOXID');
	}

    public function logs() {
        return $this->hasMany('App\Http\Models\FindTable' , 'BOXID' , 'BOXID');
    }
}
