<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class FindTable extends Model
{
	protected $primaryKey = 'ID';
    protected $table = 'find_table';
    public $timestamps = null;
    protected $fillable = ['TRANS_NUMBER' , 'UPDTIME' , 'BOXID'];
}
