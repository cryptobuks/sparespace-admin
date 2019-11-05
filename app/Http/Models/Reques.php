<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class Reques extends Model
{
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $table = 'request';
    protected $fillable = ['STATUS', 'SDETAIL', 'CONDITION', 'TRANS_NUMBER' , 'UPDTIME' , 'TRANS_NUMBER_A'];
    
    public function user(){
    	return $this->hasOne('App\Http\Models\User', 'ID', 'USER_ID');
    }
    
    public function details(){
    	return $this->hasMany('App\Http\Models\RequestDetail','REQUEST_ID','ID');
    }
}
