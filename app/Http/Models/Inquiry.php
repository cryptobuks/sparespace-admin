<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $table = 'inquiry';
    
    public $timestamps = null;
    
    
    
	public function user(){
    	return $this->hasOne('App\Http\Models\User', 'ID', 'USER_ID');
    }
}
