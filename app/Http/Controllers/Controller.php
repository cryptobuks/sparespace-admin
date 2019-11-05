<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use View;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    public $host_url = 'http://localhost:3000';

    public $CONDITION_LABELS = [
		"Custom_Delivery" => "고객 전달중" ,
		"RequestCancel_RA" => "신청취소" ,
		"RequestCancel_RH" => "신청취소(위약금)" ,
		"Returning_Standby" => "회송대기" ,
		"Returning" => "회송중" ,
		"Returning_Impropriety" => "회송불가" ,
		"Goods_Confirm" => "물품 확인중" , 
		"Finish" => "완료"
	];

    public function __construct()
    {
		date_default_timezone_set('Asia/Seoul');        
		View::share ( 'condition', 'asdf');
    }

}
