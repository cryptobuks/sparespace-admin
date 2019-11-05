<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'faq';
    
    public $timestamps = null;

    public function admin(){
    	return $this->hasOne('App\Http\Models\Admin', 'ID', 'ADMIN_ID');
    }
}
