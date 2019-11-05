<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $table = 'notice';
    
    public $timestamps = null;

    public function admin(){
    	return $this->hasOne('App\Http\Models\Admin', 'ID', 'ADMIN_ID');
    }
}
