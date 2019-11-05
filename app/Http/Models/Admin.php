<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';
    
    public $timestamps = null;
    
    public function permission(){
    	return $this->hasOne('App\Http\Models\Privilege','ID','PRIVILEGE_ID');
    }
}
