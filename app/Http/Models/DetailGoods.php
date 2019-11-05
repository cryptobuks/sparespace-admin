<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class DetailGoods extends Model
{
    protected $table = 'detail_goods';
    
    public $timestamps = null;
    
    public function detail(){
    	return $this->hasOne('App\Http\Models\RequestDetail', 'BOXID', 'BOXID');
    }
}
