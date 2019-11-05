<?php

namespace App\Http\Controllers;

use DB;


use App\Http\Models\Privilege;

use App\Http\Models\Admin;

use App\Http\Models\Coupon;

use App\Http\Models\CouponUser;

use App\Http\Models\Banner;

use App\Http\Models\Faq;

use App\Http\Models\Notice;

use App\Http\Models\PushNotification;

use App\Http\Models\Box;

use App\Http\Models\Inquiry;

use App\Http\Models\PaymentLog;

use App\Http\Models\DetailGoods;

use App\Http\Models\RequestDetail;

use Illuminate\Http\Request;

use App\Http\Models\Reques;

use App\Http\Models\User;

use DateTime;

class AjaxController extends Controller
{
	const STATUS_LABELS = [
		"PROGRESS" => "진행중" ,
		"STORAGE" => "보관중" ,
		"FINISH" => "종료" ,
		"CANCEL" => "취소"
	];
	const SDETAIL_LABELS = [
		"SHIPPING" => '배송중',
		"ARRANGE" => '물품정리중',
		"COLLECT" => '회수중',
		"CHECK" => '검수중',
		"STORAGE" => '보관중'
	];
	const CONDITION_LABELS = [
		"Custom_Delivery" => "고객 전달중" ,
		"RequestCancel_RA" => "신청취소" ,
		"RequestCancel_RH" => "신청취소(위약금)" ,
		"Returning_Standby" => "회송대기" ,
		"Returning" => "회송중" ,
		"Returning_Impropriety" => "회송불가" ,
		"Goods_Confirm" => "물품 확인중" , 
		"Finish" => "완료"
	];
        
	public function getAcceptStatusListForExcel(Request $request) {
		$request_status = $request->input("request_status");
		$ret = array();

		if($request_status == 'PROGRESS') {
			$q = RequestDetail::select('request.*')
				->leftJoin('request', 'request.ID', '=', 'request_detail.REQUEST_ID')
				->leftJoin('users', 'request.USER_ID', '=', 'users.ID')
				->where('request.STATUS', $request_status)
				->where(function($query) {
					$query->where('request_detail.MANAGE_STATUS' , '=' , 0)
					->orWhere('request_detail.MANAGE_STATUS' , '=' , 1)
					->orWhere('request_detail.MANAGE_STATUS' , '=' , 2)
					->orWhere('request_detail.MANAGE_STATUS' , '=' , 3);
				});

			if($request->input("member_id"))
				$q->where(DB::raw('CONCAT(users.USERID , IF( users.`EXIT_YN` = "Y" , "(탈퇴)" , ""))'), 'LIKE', "%{$request->member_id}%");
			if($request->input("mobile_no"))
				$q->where('users.USER_CELL', 'LIKE', "%{$request->mobile_no}%");
			if($request->input("progress_detail"))
				$q->where('request.SDETAIL', 'LIKE', "%{$request->progress_detail}%");

			if($request->input("box_id"))
				$q->where(DB::raw('CONCAT(request_detail.BOXID , IF( request_detail.`MANAGE_STATUS` = 3 , "(반송)" , ""))'), 'LIKE', "%{$request->box_id}%");

			if($request->input("shipping_no"))
				$q->where('request.TRANS_NUMBER', 'LIKE', "%{$request->shipping_no}%");
			if($request->input("status"))
				$q->where('request.CONDITION', 'LIKE', "%{$request->status}%");
			if($request->input("processing_date_from"))
				$q->where('request.UPDTIME', '>=', strtotime($request->processing_date_from));			
			if($request->input("processing_date_to"))
				$q->where('request.UPDTIME', '<', strtotime($request->processing_date_to)  + 86400);				
			$request_collection = $q->groupBy('request.ID')->get();

			foreach ($request_collection as $item) {
				$details = Reques::find($item->ID)->details;
				$box_array = array();
				foreach($details as $detail){
					if($detail->MANAGE_STATUS != 4 && $detail->MANAGE_STATUS != 5) {
						$boxid = $detail->BOXID;
		    			if($detail->MANAGE_STATUS == 3){
		    				$boxid .="(반송)";
		    			}
		    			$box_array[] = $boxid;	
					}
	    		}
	    		$edit_url = url('/acceptstatus/edit', ['id' => $item->ID]);
	    		$table_item = array(
	    			$item->ID,
	    			$item->user->USERID.($item->user->EXIT_YN=='Y'?'(탈퇴)':''),
	    			$item->user->USER_CELL,
	    			$item->ADDRESS,
	    			self::SDETAIL_LABELS[$item->SDETAIL],
	    			implode(", ",$box_array),
	    			$item->TRANS_NUMBER,
	    			self::CONDITION_LABELS[$item->CONDITION],
					date("Y-m-d h:i:sa", $item->UPDTIME)
	    		);
				$ret[] = $table_item;	
			}
		}	
		else if($request_status == 'STORAGE') {
			$q = RequestDetail::select('request_detail.*')
				->leftJoin('request', 'request.ID', '=', 'request_detail.REQUEST_ID')
				->leftJoin('users', 'request.USER_ID', '=', 'users.ID')
				->where('request_detail.MANAGE_STATUS', 4);

			if($request->member_id)
				$q->where(DB::raw('CONCAT(users.USERID , IF( users.`EXIT_YN` = "Y" , "(탈퇴)" , ""))'), 'LIKE', "%{$request->member_id}%");
			if($request->mobile_no)
				$q->where('users.USER_CELL', 'LIKE', "%{$request->input('mobile_no')}%");
			if($request->box_id)
				$q->where('request_detail.BOXID', 'LIKE', "%{$request->input('box_id')}%");
			if($request->shipping_no)
				$q->where('request.TRANS_NUMBER', 'LIKE', "%{$request->input('shipping_no')}%");
			if($request->processing_date_from)
				$q->where('request_detail.UPDTIME', '>=', strtotime($request->input('processing_date_from')));
			if($request->processing_date_to)
				$q->where('request_detail.UPDTIME', '<', strtotime($request->input('processing_date_to'))  + 86400);

			$request_detail_collection = $q->get();
			foreach($request_detail_collection as $item){
    			$today = new \DateTime('now');
				$end_date = new \DateTime($item->END_DATE);
				$diff = $end_date->diff($today);
				$additional_string = '';
				if($diff->days > 0) {
					$additional_string = '('.($diff->days+1).'일 후 자동 연장)';
				}
    			$table_item = array(
    				$item->ID,
    				$item->user->USERID.($item->user->EXIT_YN=='Y'?'(탈퇴)':''),
    				$item->user->USER_CELL,
    				$item->request->ADDRESS,
    				$item->BOXID,
    				$item->GOODS_COUNT.'개',
    				$item->START_DATE,
    				$item->STORAGE_MONTHS.'개월',
    				($item->AUTO_NEW_MONTHS>0?$item->AUTO_NEW_MONTHS.'개월 ':'').$additional_string,
    				(count($item->logs) == 0) ? '' : (count($item->logs).'건')			
    			);
    			$ret[] = $table_item;
    		}			
		}
		else if($request_status == 'FINISH') {
			$q = RequestDetail::select('request_detail.*')
				->leftJoin('request', 'request.ID', '=', 'request_detail.REQUEST_ID')
				->leftJoin('users', 'request.USER_ID', '=', 'users.ID')
				->where('request_detail.MANAGE_STATUS', 5);
			if($request->input('member_id'))
					$q->where(DB::raw('CONCAT(users.USERID , IF( users.`EXIT_YN` = "Y" , "(탈퇴)" , ""))'), 'LIKE', "%{$request->member_id}%");
			if($request->input('mobile_no'))
				$q->where('users.USER_CELL', 'LIKE', "%{$request->input('mobile_no')}%");
			if($request->input('box_id'))
				$q->where('request_detail.BOXID', 'LIKE', "%{$request->input('box_id')}%");
			if($request->input('status'))
				$q->where('request_detail.BOXID', 'LIKE', "%{$request->input('status')}%");
			if($request->input('start_date_from'))
				$q->where('request_detail.START_DATE', '>=', $request->input('start_date_from'));
			if($request->input('start_date_to'))
				$q->where('request_detail.START_DATE', '<=', $request->input('start_date_to'));
			if($request->input('end_date_from'))
				$q->where('request_detail.END_DATE', '>=', $request->input('end_date_from'));
			if($request->input('end_date_to'))
				$q->where('request_detail.END_DATE', '<=', $request->input('end_date_to'));
			if($request->input('shipping_no'))
				$q->where('request.TRANS_NUMBER', 'LIKE', "%{$request->input('shipping_no')}%");
			if($request->input('processing_date_from'))
				$q->where('request_detail.UPDTIME', '>=', strtotime($request->input('processing_date_from')));
			if($request->input('processing_date_to'))
				$q->where('request_detail.UPDTIME', '<', strtotime($request->input('processing_date_to'))  + 86400 );
			$request_detail_collection = $q->get();
				
			foreach($request_detail_collection as $item){
    			$today = new \DateTime('now');
				$end_date = new \DateTime($item->END_DATE);
				$diff = $end_date->diff($today);
				$additional_string = '';
				if($diff->days > 0) {
					$additional_string = ' ('.($diff->days+1).'일 남음)';
				}
    			$table_item = array(
    				$item->ID,
    				$item->user->USERID.($item->user->EXIT_YN=='Y'?'(탈퇴)':''),
    				$item->user->USER_CELL,
    				$item->request->ADDRESS,
    				$item->BOXID,
    				$item->TRANS_NUMBER , 
    				self::CONDITION_LABELS[$item->CONDITION].$additional_string,
    				$item->START_DATE,
    				$item->END_DATE,
    				$item->STORAGE_MONTHS.'개월',
    				$item->AUTO_NEW_MONTHS>0?$item->AUTO_NEW_MONTHS.'개월':'',
    				(count($item->logs) == 0) ? '' : (count($item->logs).'건')			
    			);
    			$ret[] = $table_item;
    		}
		}

		return response()->json(array('data' => $ret, "status" => 'success'));
	}

    public function getAcceptStatusList(Request $request){

    	$pageLength = intval($request->input('length'));
    	$start = intval($request->input('start'));
    	$data_count = 0;
    	$ret = [];
    	
    	$request_status = "PROGRESS";
    		
    	if($request->has("request_status"))
    	{
    		$request_status = $request->input("request_status");
    	}
		
    	if($request_status == "PROGRESS")
    	{
			$q = RequestDetail::select('request.*')
				->leftJoin('request', 'request.ID', '=', 'request_detail.REQUEST_ID')
				->leftJoin('users', 'request.USER_ID', '=', 'users.ID')
				->where('request.STATUS', $request_status)
				->where(function($query) {
					//->where('request_detail.MANAGE_STATUS = ? or request_detail.MANAGE_STATUS = ? or request_detail.MANAGE_STATUS = ? or request_detail.MANAGE_STATUS = ?', [0 , 1 , 2 , 3]);
					$query->where('request_detail.MANAGE_STATUS' , '=' , 0)
					->orWhere('request_detail.MANAGE_STATUS' , '=' , 1)
					->orWhere('request_detail.MANAGE_STATUS' , '=' , 2)
					->orWhere('request_detail.MANAGE_STATUS' , '=' , 3);
				});

			if($request->member_id)
				$q->where(DB::raw('CONCAT(users.USERID , IF( users.`EXIT_YN` = "Y" , "(탈퇴)" , ""))'), 'LIKE', "%{$request->member_id}%");
			if($request->mobile_no)
				$q->where('users.USER_CELL', 'LIKE', "%{$request->mobile_no}%");
			if($request->progress_detail)
				$q->where('request.SDETAIL', 'LIKE', "%{$request->progress_detail}%");
			if($request->box_id)
				$q->where(DB::raw('CONCAT(request_detail.BOXID , IF( request_detail.`MANAGE_STATUS` = 3 , "(반송)" , ""))'), 'LIKE', "%{$request->box_id}%");
				//$q->where('request_detail.BOXID', 'LIKE', "%{$request->box_id}%");
			if($request->shipping_no)
				$q->where('request.TRANS_NUMBER', 'LIKE', "%{$request->shipping_no}%");
			if($request->status)
				$q->where('request.CONDITION', 'LIKE', "%{$request->status}%");
			if($request->processing_date_from)
				$q->where('request.UPDTIME', '>=', strtotime($request->processing_date_from));
			if($request->processing_date_to)
				$q->where('request.UPDTIME', '<', strtotime($request->processing_date_to) + 86400);
			$data_count = count($q->groupBy('request.ID')->get());
			$request_collection = $q->groupBy('request.ID')->orderBy('request.UPDTIME' , 'desc')->offset($start)->limit($pageLength)->get();
	    	foreach($request_collection as $item){
	    		$details = Reques::find($item->ID)->details;
	    		$box_array = array();

	    		foreach($details as $detail){
	    			if($detail->MANAGE_STATUS != 4 && $detail->MANAGE_STATUS != 5) {
	    				$boxid = $detail->BOXID;
		    			$boxid_text = '';

		    			if($detail->MANAGE_STATUS == 3){
		    				$boxid_text = $boxid;	
		    				$boxid_text .="(반송)";
		    			}
		    			else {
		    				$boxid_text = $boxid;	
		    			}

		    			if($detail->MANAGE_STATUS == 1 || $detail->MANAGE_STATUS == 2 || $detail->MANAGE_STATUS == 3 || $detail->MANAGE_STATUS == 4) 
		    				$box_array[] = '<a href="'.url('/goodsinquiry/view').'?box_id='.$boxid.'">'.$boxid_text.'</a>';
		    			else 
		    				$box_array[] = $boxid;
	    			}
	    		}
				$edit_url = url('/acceptstatus/edit', ['id' => $item->ID]);

	    		$table_item = array(
	    			'<input type="checkbox" id="progess_list'.$item->ID.'" data-id="'.$item->ID.'" class="progress_list" />',
	    			$item->ID,
	    			$item->user->USERID.($item->user->EXIT_YN=='Y'?'(탈퇴)':''),
	    			$item->user->USER_CELL,
	    			$item->ADDRESS,
	    			self::SDETAIL_LABELS[$item->SDETAIL],
	    			implode(", ",$box_array),
	    			'<a href="#" onclick="trans_click(\''.$item->TRANS_NUMBER.'\')">'.$item->TRANS_NUMBER.'</a>',
	    			($item->CONDITION == 'RequestCancel_RH' || $item->CONDITION == 'Returning') ? ('<a href="#" onclick="add_trans_click(\''.$item->ID.'\')">'.self::CONDITION_LABELS[$item->CONDITION].'</a>') : self::CONDITION_LABELS[$item->CONDITION] , 
					date("Y-m-d h:i:sa", $item->UPDTIME)
	    		);
				$ret[] = $table_item;
	    	}
    	}else if( $request_status == "STORAGE" ){
			$q = RequestDetail::select('request_detail.*')
				->leftJoin('request', 'request.ID', '=', 'request_detail.REQUEST_ID')
				->leftJoin('users', 'request.USER_ID', '=', 'users.ID')
				->where('request_detail.MANAGE_STATUS', 4);
			if($request->member_id)
					$q->where(DB::raw('CONCAT(users.USERID , IF( users.`EXIT_YN` = "Y" , "(탈퇴)" , ""))'), 'LIKE', "%{$request->member_id}%");
			if($request->mobile_no)
				$q->where('users.USER_CELL', 'LIKE', "%{$request->input('mobile_no')}%");
			if($request->box_id)
				$q->where('request_detail.BOXID', 'LIKE', "%{$request->input('box_id')}%");
			if($request->shipping_no)
				$q->where('request.TRANS_NUMBER', 'LIKE', "%{$request->input('shipping_no')}%");
			if($request->processing_date_from)
				$q->where('request_detail.UPDTIME', '>=', strtotime($request->input('processing_date_from')));
			if($request->processing_date_to)
				$q->where('request_detail.UPDTIME', '<', strtotime($request->input('processing_date_to')) + 86400);
			$data_count = count($q->get());
			$request_detail_collection = $q->orderBy('request_detail.UPDTIME','desc')->offset($start)->limit($pageLength)->get();
    		//$request_detail_collection = RequestDetail::all();
    		foreach($request_detail_collection as $item){
    			$today = new \DateTime('now');
				$end_date = new \DateTime($item->END_DATE);
				$diff = $end_date->diff($today);
				$additional_string = '';
				if($diff->days > 0) {
					$additional_string = '('.($diff->days+1).'일 후 자동 연장)';
				}

    			$table_item = array(
    				$item->ID,
    				$item->user->USERID.($item->user->EXIT_YN=='Y'?'(탈퇴)':''),
    				$item->user->USER_CELL,
    				$item->request->ADDRESS,
    				'<a href="'.url('/goodsinquiry/view').'?box_id='.$item->BOXID.'">'.$item->BOXID.'</a>' ,
    				$item->GOODS_COUNT.'개',
    				$item->START_DATE,
    				$item->STORAGE_MONTHS.'개월',
    				($item->AUTO_NEW_MONTHS>0?$item->AUTO_NEW_MONTHS.'개월 ':'').$additional_string,
    				(count($item->logs) == 0) ? '' : ('<a href="#" onclick="logs_click(\''.$item->BOXID.'\')">'.count($item->logs).'건</a>')			
    			);
    			$ret[] = $table_item;

    		}
    	} else if( $request_status == "FINISH" ) {
    		$q = RequestDetail::select('request_detail.*')
				->leftJoin('request', 'request.ID', '=', 'request_detail.REQUEST_ID')
				->leftJoin('users', 'request.USER_ID', '=', 'users.ID')
				->where('request_detail.MANAGE_STATUS', 5);
			if($request->input('member_id'))
				$q->where(DB::raw('CONCAT(users.USERID , IF( users.`EXIT_YN` = "Y" , "(탈퇴)" , ""))'), 'LIKE', "%{$request->member_id}%");
			if($request->input('mobile_no'))
				$q->where('users.USER_CELL', 'LIKE', "%{$request->input('mobile_no')}%");
			if($request->input('box_id'))
				$q->where('request_detail.BOXID', 'LIKE', "%{$request->input('box_id')}%");
			if($request->input('status'))
				$q->where('request_detail.BOXID', 'LIKE', "%{$request->input('status')}%");
			if($request->input('start_date_from'))
				$q->where('request_detail.START_DATE', '>=', $request->input('start_date_from'));
			if($request->input('start_date_to'))
				$q->where('request_detail.START_DATE', '<=', $request->input('start_date_to'));
			if($request->input('end_date_from'))
				$q->where('request_detail.END_DATE', '>=', $request->input('end_date_from'));
			if($request->input('end_date_to'))
				$q->where('request_detail.END_DATE', '<=', $request->input('end_date_to'));
			if($request->input('shipping_no'))
				$q->where('request_detail.TRANS_NUMBER', 'LIKE', "%{$request->input('shipping_no')}%");
			if($request->input('processing_date_from'))
				$q->where('request_detail.UPDTIME', '>=', strtotime($request->input('processing_date_from')));
			if($request->input('processing_date_to'))
				$q->where('request_detail.UPDTIME', '<', strtotime($request->input('processing_date_to')) + 86400);

			$data_count = count($q->get());
			$request_detail_collection = $q->orderBy('request_detail.UPDTIME' , 'desc')->offset($start)->limit($pageLength)->get();
			
			foreach($request_detail_collection as $item){
    			$today = new \DateTime('now');
				$end_date = new \DateTime($item->END_DATE);
				$diff = $end_date->diff($today);
				$additional_string = '';
				if($diff->days > 0) {
					$additional_string = '<br/>('.($diff->days+1).'일 남음)';
				}

    			$table_item = array(
    				$item->ID,
    				$item->user->USERID.($item->user->EXIT_YN=='Y'?'(탈퇴)':''),
    				$item->user->USER_CELL,
    				$item->request->ADDRESS,
    				$item->BOXID,
    				'<a href="#" onclick="trans_click(\''.$item->TRANS_NUMBER.'\')">'.$item->TRANS_NUMBER.'</a>',
    				($item->CONDITION == NULL || $item->CONDITION == '') ? '' : self::CONDITION_LABELS[$item->CONDITION].$additional_string ,
    				$item->START_DATE,
    				$item->END_DATE,
    				$item->STORAGE_MONTHS.'개월',
    				$item->AUTO_NEW_MONTHS>0?$item->AUTO_NEW_MONTHS.'개월':'',
    				(count($item->logs) == 0) ? '' : ('<a href="#" onclick="logs_click(\''.$item->BOXID.'\')">'.count($item->logs).'건</a>')
    			);
    			$ret[] = $table_item;
    		}
    			
    	}
    	return response()->json(array('data' => $ret, "draw" => 0,"recordsTotal"=> $data_count,"recordsFiltered"=>$data_count));
	}
	
	public function getDetailGoods(Request $request){
		$box_id = $request->input('box_id');
		$goods_collection = DetailGoods::where('BOXID', $box_id)->get();
		
		$ret = [];
		
		foreach($goods_collection as $item){
			$table_item = array(
				$item->ID,
				$item->GOODS_NAME,
				date("Y-m-d h:i:sa",$item->UPDTIME),
				$item->TRANS_NUMBER,
				$item->STATUS
			);
			
			$ret[] = $table_item;
		}
		return response()->json(array('data' => $ret, "draw" => 0,"recordsTotal"=> count($ret),"recordsFiltered"=>count($ret)));
	}
	
	public function getMemberList(Request $request){

		$pageLength = intval($request->input('length'));
    	$start = intval($request->input('start'));

		//$user_collecction = User::all();
    	$user_collecction = User::select('users.*')->orderBy('users.REGTIME' , 'desc')->get();
		$ret = [];
		foreach($user_collecction as $user){
			$user_request_collection = $user->request;
			$status_array = array();
			$progress = false; $storage = false; $finish = false;	
			foreach($user_request_collection as $item){
				if($item->STATUS == 'PROGRESS') {
					$progress = true;
					break;
				}
			}
			$request_detail_collection = $user->request_detail;
			foreach($request_detail_collection as $item) {
				if($item->MANAGE_STATUS == 4) {
					$storage = true;
					break;
				}
			}
			foreach($request_detail_collection as $item) {
				if($item->MANAGE_STATUS == 5) {
					$finish = true;
					break;
				}
			}
			if($progress)
				$status_array[] = '진행중';
			if($storage)
				$status_array[] = '보관중';
			if($finish)
				$status_array[] = '종료';
			$table_item = array(
				$user->ID,
				'<a href="'.url('/member/memberview?user_id=').$user->ID.'">'.$user->USERID.($user->EXIT_YN=='Y'?'(탈퇴)':'').'</a>',
				$user->USER_EMAIL,
				$user->USER_CELL,
				$user->ADDR1." ".$user->ADDR2." ".$user->DETAIL_ADDR ,
				implode("/", $status_array),
				date("Y-m-d h:i:sa",$user->UPDTIME)
			);

			$user->USERID = $user->USERID.($user->EXIT_YN=='Y'?'(탈퇴)':'');

			if($request->has("member_id")){
    			if(strrpos($user->USERID, $request->input("member_id")) === false)
    			{
    				continue;
    			}
    		} 
    		if($request->has("email")){
    			if(strrpos($user->USER_EMAIL, $request->input("email")) === false)
    			{
    				continue;
    			}
    		} 
    		if($request->has("mobile_no")){
    			if(strrpos($user->USER_CELL, $request->input("mobile_no")) === false)
    			{
    				continue;
    			}
    		}	
			if($request->has("processing_date_from")){
    			if($user->UPDTIME < strtotime($request->input("processing_date_from")))
    			{
    				continue;
    			}
    		}	
    		if($request->has("processing_date_to")){
    			if($user->UPDTIME >= strtotime($request->input("processing_date_to")) + 86400)
    			{
    				continue;
    			}
    		}	
    		$compare_status = '';
    		if($request->input("status") == 'PROGRESS')
    			$compare_status = '진행중';
    		else if($request->input("status") == 'STORAGE')
    			$compare_status = '보관중';
    		else if($request->input("status") == 'FINISH')
    			$compare_status = '종료';
    		else 
    			$compare_status = $request->input("status");
    		if($request->has("status")){
    			if($compare_status != "all" && $compare_status != "none"){
    				if(in_array($compare_status, $status_array) == false){
    					continue;
    				}
    				
    			}else if($compare_status == "none" && count($status_array) != 0){
    					continue;
    			} 
    		}
			$ret[] = $table_item;
		}

		$data_ret = [];
		$k = 0;
		while(1) {
			if($k >= count($ret))
				break;
			if($k >= $start && $k < $start + $pageLength) {
				$data_ret[] = $ret[$k];
			}
			$k ++;
		}
		return response()-> json(array('data' => $data_ret, "draw" => 0,"recordsTotal"=> count($ret),"recordsFiltered"=>count($ret)));
	}
	
	public function getAcceptStatusListByUserId(Request $request){
		$ret = [];
    	$user_id = $request->input("user_id");
    	$action = $request->input("action");
    	
    	if($action == "PROGRESS")
    	{
    		$request_collection = Reques::where(array('USER_ID'=>$user_id , "STATUS" => "PROGRESS"))->get();

	    	foreach($request_collection as $item){
	    		$box_collection = $item->details;
	    		$box_array = array();

	    		foreach($box_collection as $detail){
	    			if($detail->MANAGE_STATUS != 4 && $detail->MANAGE_STATUS != 5) {
	    				$boxid = $detail->BOXID;
		    			$boxid_text = '';
		    			if($detail->MANAGE_STATUS == 3){
		    				$boxid_text = $boxid;	
		    				$boxid_text .="(반송)";
		    			}
		    			else {
		    				$boxid_text = $boxid;	
		    			}
		    			if($detail->MANAGE_STATUS == 1 || $detail->MANAGE_STATUS == 2 || $detail->MANAGE_STATUS == 3 || $detail->MANAGE_STATUS == 4) 
		    				$box_array[] = '<a href="'.url('/goodsinquiry/view').'?box_id='.$boxid.'">'.$boxid_text.'</a>';
		    			else 
		    				$box_array[] = $boxid;	
	    			}
	    		}

	    		$table_item = array(
	    			$item->ID,
	    			self::SDETAIL_LABELS[$item->SDETAIL],
	    			implode("/",$box_array),
	    			'<a href="#" onclick="trans_click(\''.$item->TRANS_NUMBER.'\')">'.$item->TRANS_NUMBER.'</a>',
	    			self::CONDITION_LABELS[$item->CONDITION],
	    			date("Y-m-d h:i:sa",$item->UPDTIME)
	    		);    		
	    		if(count($box_array) > 0)
	    			$ret[] = $table_item;
	    	}
    	}else if($action == "STORAGE"){
    		$request_detail_collection = RequestDetail::where(array('USER_ID'=>$user_id , 'MANAGE_STATUS'=>4) )->get();
    		foreach($request_detail_collection as $item){
    			
    			$today = new \DateTime('now');
				$end_date = new \DateTime($item->END_DATE);
				$diff = $end_date->diff($today);
				$additional_string = '';
				if($diff->days > 0) {
					$additional_string = '('.$diff->days.'일 후 자동 연장)';
				}

    			$table_item = array(
    				$item->request->ID,
    				'<a href="'.url('/goodsinquiry/view').'?box_id='.$item->BOXID.'">'.$item->BOXID.'</a>',
    				$item->GOODS_COUNT.'개',
    				$item->START_DATE,
    				$item->STORAGE_MONTHS.'개월',
    				($item->AUTO_NEW_MONTHS>0?$item->AUTO_NEW_MONTHS.'개월 ':'').$additional_string,
    				$action == "STORAGE"?'<a href="'.url('/acceptstatus/storage').'?box_id='.$item->BOXID.'" class="text_note">'.$item->NOTE.'</a>':$item->NOTE
    			);
    			$ret[] = $table_item;
    		}
    	}else if($action == "FINISH"){
    		$request_detail_collection = RequestDetail::where(array('USER_ID' => $user_id , 'MANAGE_STATUS' => 5) )->get();
    		foreach($request_detail_collection as $item){
    			$today = new \DateTime('now');
				$end_date = new \DateTime($item->END_DATE);
				$diff = $end_date->diff($today);
				$additional_string = '';
				if($diff->days > 0) {
					$additional_string = '<br/>('.$diff->days.'일 남음)';
				}
    			$table_item = array(
    				$item->ID,
    				$item->BOXID,
    				'<a href="#" onclick="trans_click(\''.$item->TRANS_NUMBER.'\')">'.$item->TRANS_NUMBER.'</a>',
    				self::CONDITION_LABELS[$item->request->CONDITION].$additional_string,
    				$item->START_DATE,
    				$item->END_DATE,
    				$item->STORAGE_MONTHS.'개월',
    				$item->AUTO_NEW_MONTHS>0?$item->AUTO_NEW_MONTHS.'개월':'',
    				""
    			);
    			$ret[] = $table_item;
    		}
    	}

    	$pageLength = intval($request->input('length'));
    	$start = intval($request->input('start'));
    	$data_ret = [];

    	$k = 0;
		while(1) {
			if($k >= count($ret))
				break;
			if($k >= $start && $k < $start + $pageLength) {
				$data_ret[] = $ret[$k];
			}
			$k ++;
		}
		return response()-> json(array('data' => $data_ret, "draw" => 0,"recordsTotal"=> count($ret),"recordsFiltered"=>count($ret)));
	}
	
	public function getPaymentLog(Request $request){

		$pageLength = intval($request->input('length'));
    	$start = intval($request->input('start'));
		$data_count = 0;

		$payment_collection = PaymentLog::select('payment_log.*')
		->leftJoin('users', 'payment_log.USER_ID', '=', 'users.ID');
		
        if($request->input("member_id"))
			$payment_collection->where(DB::raw('CONCAT(users.USERID , IF( users.`EXIT_YN` = "Y" , "(탈퇴)" , ""))'), 'LIKE', "%{$request->member_id}%");
		if($request->input("mobile_no"))
			$payment_collection->where('users.USER_CELL', 'LIKE', "%{$request->mobile_no}%");
		if($request->input("status") && $request->input("status") != "all")
			$payment_collection->where('payment_log.STATUS', 'LIKE', "%{$request->status}%");
		if($request->input("processing_date_from"))
			$payment_collection->where('payment_log.UPDTIME', '>=', strtotime($request->processing_date_from));			
		if($request->input("processing_date_to"))
			$payment_collection->where('payment_log.UPDTIME', '<', strtotime($request->processing_date_to) + 86400);				
		if($request->input("cost_from"))
			$payment_collection->where('payment_log.COST', '>=', $request->cost_from);			
		if($request->input("cost_to"))
			$payment_collection->where('payment_log.COST', '<=', $request->cost_to);
		if( $request->input("user_id") != NULL && $request->input("user_id") != '' ) {
			$payment_collection->where('payment_log.USER_ID', '=', $request->input("user_id"));			
		}
		$data_count = count($payment_collection->get());
		$payment_collection = $payment_collection->orderBy('payment_log.UPDTIME' , 'desc')->offset($start)->limit($pageLength)->get();
		//var_dump($payment_collection);
		//exit;
		$ret = [];
		//$data_count = count($q->groupBy('request.ID')->get());
		//$request_collection = $q->groupBy('request.ID')->offset($start)->limit($pageLength)->get();
		foreach($payment_collection as $item){
			$table_item = array(
				$item->ID,
				self::STATUS_LABELS[$item->STATUS] ,
				$item->STATUS != 'PROGRESS'?'':self::SDETAIL_LABELS[$item->SDETAIL],
				$item->user->USERID.($item->user->EXIT_YN=='Y'?'(탈퇴)':''),
				$item->user->USER_CELL,
				'<a onclick="payment_modal(\''.$item->ID .'\')">'.$item->PAY_DESC.'</a>',
				date("Y-m-d h:i:sa",$item->UPDTIME),
				number_format($item->COST , 0 , '.' , ',').'원'
			); 
			$ret[] = $table_item;
		}
		return response()-> json(array('data' => $ret, "draw" => 0,"recordsTotal"=> $data_count,"recordsFiltered"=>$data_count));
	}
	
	public function getPaymentDetail(Request $request) {
		$id = $request->input('id');
		//$inquiry = Inquiry::where("ID" , $inquiry_id);
		$detail = PaymentLog::find($id);
		return response()->json(array('data'=>$detail , 'status'=>'success'));
	}

	public function getInquiryList(Request $request){
		$inquiry_collection = Inquiry::all();
		$ret = [];
		
		//$status = array("Unconfirmed", "Answer Completed", "Check Contents");
		
		foreach($inquiry_collection as $item){

			$table_item = array(
				$item->ID,
				$item->user->USERID.($item->user->EXIT_YN=='Y'?'(탈퇴)':''),
				$item->user->USER_EMAIL,
				'<a href="'.url('/member/inquiryview?id=').$item->ID.'">'.$item->QUERY.'</a>',
				date("Y-m-d h:i:sa",$item->UPDTIME),
				$item->STATUS
			);
					

			$item->user->USERID = $item->user->USERID.($item->user->EXIT_YN=='Y'?'(탈퇴)':'');
				if($request->has("member_id")){
	    			if(strrpos($item->user->USERID, $request->input("member_id")) === false)
	    			{
	    				continue;
	    			}
	    		} 
	
	    		if($request->has("email")){
	    			if(strrpos($item->user->USER_EMAIL, $request->input("email")) === false)
	    			{
	    				continue;
	    			}
	    		}	    		
	    		
	    		if($request->has("inquiry_time_to")){
	    			if($item->REGTIME >= strtotime($request->input("inquiry_time_to"))  + 86400)
	    			{
	    				continue;
	    			}
	    		}
	    		
    			if($request->has("inquiry_time_from")){
	    			if($item->REGTIME < strtotime($request->input("inquiry_time_from")))
	    			{
	    				continue;
	    			}
	    		}
	    		
				if($request->has("status")){
	    			if($request->input("status") != "all" && strrpos($item->STATUS, $request->input("status")) === false)
	    			{
	    				continue;
	    			}
	    		}

				if($request->has("contents")){
	    			if(strrpos($item->ANSWER, $request->input("contents")) === false && strrpos($item->QUERY, $request->input("contents")) === false)
	    			{
	    				continue;
	    			}
	    		}	    		
			
			$ret[] = $table_item;
		}

		for($i = 0; $i < count($ret); $i ++) {
			if($ret[$i][5] == 2)
				$ret[$i][5] = '답변완료';
			else if($ret[$i][5] == 0)
				$ret[$i][5] = '미확인';
			else if($ret[$i][5] == 1)
				$ret[$i][5] = '내용확인';
			else
				$ret[$i][5] = '미확인'; 
		}
		$pageLength = intval($request->input('length'));
		$start = intval($request->input('start'));	
		$data_ret = [];
		$k = 0;
		while(1) {
			if($k >= count($ret))
				break;
			if($k >= $start && $k < $start + $pageLength) {
				$data_ret[] = $ret[$k];
			}
			$k ++;
		}
		return response()-> json(array('data' => $data_ret, "draw" => 0,"recordsTotal"=> count($ret),"recordsFiltered"=>count($ret)));
	}
	
	public function getBoxList(Request $request){
		$box_collection = Box::all();
		
		$ret = [];
		
		foreach ($box_collection as $item){

			$box_image = '';
			if($item->IMAGE1 != '')  {
				$box_image = '<img src="'.$item->IMAGE1.'" style="width: 100px; height: 100px;" />';
			}
			if($item->IMAGE2 != '') {
				$box_image .= '<img src="'.$item->IMAGE2.'" style="width: 100px; height: 100px;" />';
			}


			$table_item = array(
				$item->ID,
				$box_image,
				'<a href="'.url('/goodsinquiry/boxview?id=').$item->ID.'">'.$item->NAME.'</a>',
				$item->WIDTH."X".$item->LENGTH."X".$item->HEIGHT,
				$item->USING_COUNT.'개',
				$item->AVAILABLE_COUNT.'개',
				$item->TOTAL_COUNT.'개'
			);
			
			$ret[] = $table_item;
		}

		$pageLength = intval($request->input('length'));
		$start = intval($request->input('start'));
		$data_ret = [];
		$k = 0;
		while(1) {
			if($k >= count($ret))
				break;
			if($k >= $start && $k < $start + $pageLength) {
				$data_ret[] = $ret[$k];
			}
			$k ++;
		}
		return response()-> json(array('data' => $data_ret, "draw" => 0,"recordsTotal"=> count($ret),"recordsFiltered"=>count($ret)));
	}
	
	public function getGoodsList(Request $request){
		$q = RequestDetail::select('request_detail.*')
				->leftJoin('request', 'request.ID', '=', 'request_detail.REQUEST_ID')
				->leftJoin('users', 'request.USER_ID', '=', 'users.ID')
				->where('request_detail.MANAGE_STATUS' , 4)
				->orWhere(['request_detail.MANAGE_STATUS' => 1 , 'request.SDETAIL' => 'CHECK'])
				->orWhere(['request_detail.MANAGE_STATUS' => 2 , 'request.SDETAIL' => 'CHECK'])
				->orWhere(['request_detail.MANAGE_STATUS' => 3 , 'request.SDETAIL' => 'CHECK'])
				->orderBy('request_detail.UPDTIME' , 'desc');
			//	->where('request_detail.MANAGE_STATUS = 4 or (request_detail.MANAGE_STATUS = 1 and request.SDETAIL="CHECK" )');
		$goods_collection = $q->get();
		$ret =[];
		foreach($goods_collection as $item){
			$stage1 = '';
			$stage2 = '';
			if($item->MANAGE_STATUS == 4)
				$stage1 = 'STORAGE';
			else 
			{
				$stage1 = 'PROGRESS';
				$stage2 = $item->request->SDETAIL;
			}
			$table_item = array(
				$item->ID,
				self::STATUS_LABELS[$stage1],
				$stage2 == '' ? '' : self::SDETAIL_LABELS[$stage2],
				"<a href='".url('goodsinquiry/view?box_id=').$item->BOXID."'>".$item->BOXID."</a>",
				$item->GOODS_COUNT,
				$item->user->USERID.($item->user->EXIT_YN=='Y'?'(탈퇴)':''),
				$item->user->USER_CELL,
				$item->END_DATE,
				$item->CHECKTIME != ""? date("Y-m-d h:i:sa",$item->CHECKTIME):"",
				(($item->MANAGE_STATUS == 2) ?"<span>검수불가</span>": (($item->MANAGE_STATUS == 3)? ('<a href="#" onclick="refund_click(\''.$item->ID.'\')">반송처리</a>'): ""))
			);
			$item->user->USERID = $item->user->USERID.($item->user->EXIT_YN=='Y'?'(탈퇴)':'');
			if($request->has("member_id")){
    			if(strrpos($item->user->USERID, $request->input("member_id")) === false)
    			{
    				continue;
    			}
    		} 
    		if($request->has("mobile_no")){
    			if(strrpos($item->user->USER_CELL, $request->input("mobile_no")) === false)
    			{
    				continue;
    			}
    		}
			if($request->has("box_id")){
    			if(strrpos($item->BOXID, $request->input("box_id")) === false)
    			{
    				continue;
    			}
    		}
			if($request->has("status")){
    			if($request->input("status") != "all" && strrpos($stage1, $request->input("status")) === false)
    			{
    				continue;
    			}
    		}
			
			$ret[] = $table_item;
		}
		$pageLength = intval($request->input('length'));
		$start = intval($request->input('start'));
		$data_ret = [];
		$k = 0;
		while(1) {
			if($k >= count($ret))
				break;
			if($k >= $start && $k < $start + $pageLength) {
				$data_ret[] = $ret[$k];
			}
			$k ++;
		}
		return response()-> json(array('data' => $data_ret, "draw" => 0,"recordsTotal"=> count($ret),"recordsFiltered"=>count($ret)));
	}
	
	public function getRequestDetailList(Request $request){
		$box_id = $request->input("box_id");
		$request_detail_collection = RequestDetail::where("BOXID", $box_id)->get();
		$ret = [];
		$status = array("");
		foreach($request_detail_collection as $item){
			$table_item = array(
				$item->ID,
				$item->MANAGE_STATUS,
				$item->TRANS_NUMBER,
				$item->NOTE
			);	
			$ret[] = $table_item;
		}
		return response()->json(array('data' => $ret, "draw" => 0,"recordsTotal"=> count($ret),"recordsFiltered"=>count($ret)));
	}
	
	public function getPushNotificationList(Request $request){
		$notification_collection = PushNotification::all();
		$ret = [];
		foreach($notification_collection as $item){
			$table_item = array(
				$item->ID,
				'<a href="'.url('administrative/push/view?id=').$item->ID.'">'.$item->TITLE.'</a>',
				$item->CONTENTS,
				($item->TYPE == 1 )? "Admin": "Auto",
				date("Y-m-d h:i:sa",$item->REGTIME)
			);
			if($request->has("title")){
    			if(strrpos($item->TITLE, $request->input("title")) === false)
    			{
    				continue;
    			}
    		}
			if($request->has("contents")){
    			if(strrpos($item->CONTENTS, $request->input("contents")) === false)
    			{
    				continue;
    			}
    		}
			if($request->has("create_date_to")){
    			if($item->REGTIME >= strtotime($request->input("create_date_to")) + 86400)
    			{
    				continue;
    			}
    		}
			if($request->has("create_date_from")){
    			if($item->REGTIME < strtotime($request->input("create_date_from")))
    			{
    				continue;
    			}
    		}
			$ret[] = $table_item;
		}

		$pageLength = intval($request->input('length'));
		$start = intval($request->input('start'));

		$data_ret = [];
		$k = 0;
		while(1) {
			if($k >= count($ret))
				break;
			if($k >= $start && $k < $start + $pageLength) {
				$data_ret[] = $ret[$k];
			}
			$k ++;
		}
		return response()-> json(array('data' => $data_ret, "draw" => 0,"recordsTotal"=> count($ret),"recordsFiltered"=>count($ret)));
	}
	
	public function getNoticeList(Request $request){
		$notice_collection = Notice::all();
		$ret = [];
		foreach($notice_collection as $item){
			$table_item = array(
				$item->ID,
				'<a href="'.url('administrative/notice/view?id=').$item->ID.'">'.$item->TITLE.'</a>',
				$item->CONTENTS,
				$item->admin->ADMIN_NAME,
				date("Y-m-d h:i:sa",$item->REGTIME)
			);
			
			if($request->has("title")){
    			if(strrpos($item->TITLE, $request->input("title")) === false)
    			{
    				continue;
    			}
    		}
    		
			if($request->has("contents")){
    			if(strrpos($item->CONTENTS, $request->input("contents")) === false)
    			{
    				continue;
    			}
    		}
    		
			if($request->has("create_date_to")){
    			if($item->REGTIME >= strtotime($request->input("create_date_to")) + 86400)
    			{
    				continue;
    			}
    		}
    		
			if($request->has("create_date_from")){
    			if($item->REGTIME < strtotime($request->input("create_date_from")))
    			{
    				continue;
    			}
    		}
			
			$ret[] = $table_item;
		}
		$pageLength = intval($request->input('length'));
		$start = intval($request->input('start'));	
		$data_ret = [];
		$k = 0;
		while(1) {
			if($k >= count($ret))
				break;
			if($k >= $start && $k < $start + $pageLength) {
				$data_ret[] = $ret[$k];
			}
			$k ++;
		}
		return response()-> json(array('data' => $data_ret, "draw" => 0,"recordsTotal"=> count($ret),"recordsFiltered"=>count($ret)));
	}
	
	public function getFAQList(Request $request){
		$faq_collection = Faq::all();
		$ret = [];
		foreach($faq_collection as $item){

			$table_item = array(
				$item->ID,
				'<a href="'.url('administrative/faq/view?id=').$item->ID.'">'.$item->TITLE.'</a>',
				$item->CONTENTS,
				$item->admin->ADMIN_NAME,
				date("Y-m-d h:i:sa",$item->REGTIME)
			);
			
			if($request->has("title")){
    			if(strrpos($item->TITLE, $request->input("title")) === false)
    			{
    				continue;
    			}
    		}
    		
			if($request->has("contents")){
    			if(strrpos($item->CONTENTS, $request->input("contents")) === false)
    			{
    				continue;
    			}
    		}
    		
			if($request->has("create_date_to")){
    			if($item->REGTIME >= strtotime($request->input("create_date_to"))  + 86400)
    			{
    				continue;
    			}
    		}
    		
			if($request->has("create_date_from")){
    			if($item->REGTIME < strtotime($request->input("create_date_from")))
    			{
    				continue;
    			}
    		}
			
			$ret[] = $table_item;
		}
		$pageLength = intval($request->input('length'));
		$start = intval($request->input('start'));
		$data_ret = [];
		$k = 0;
		while(1) {
			if($k >= count($ret))
				break;
			if($k >= $start && $k < $start + $pageLength) {
				$data_ret[] = $ret[$k];
			}
			$k ++;
		}
		return response()->json(array('data' => $data_ret, "draw" => 0,"recordsTotal"=> count($ret),"recordsFiltered"=>count($ret)));
	}
	
	public function getBannerList(Request $request){
		$banner_collection = Banner::all();
		$ret = [];
		foreach($banner_collection as $item){
			$table_item = array(
				$item->ID,
				$item->IMAGE != ""?"<img src='".url($item->IMAGE)."' style='width:300px; height:300px;' />": "",
				'<a href="'.url('administrative/banner/view?id=').$item->ID.'">'.$item->TITLE.'</a>',
				$item->CONTENTS,
				date("Y-m-d h:i:sa",$item->REGTIME)
			);
			
			if($request->has("title")){
    			if(strrpos($item->TITLE, $request->input("title")) === false)
    			{
    				continue;
    			}
    		}
    		
			if($request->has("contents")){
    			if(strrpos($item->CONTENTS, $request->input("contents")) === false)
    			{
    				continue;
    			}
    		}
    		
			if($request->has("create_date_to")){
    			if($item->REGTIME >= strtotime($request->input("create_date_to")) + 86400)
    			{
    				continue;
    			}
    		}
    		
			if($request->has("create_date_from")){
    			if($item->REGTIME < strtotime($request->input("create_date_from")))
    			{
    				continue;
    			}
    		}
    		
			$ret[] = $table_item;
		}

		$pageLength = intval($request->input('length'));
		$start = intval($request->input('start'));

		$data_ret = [];
		$k = 0;

		while(1) {
			if($k >= count($ret))
				break;
			if($k >= $start && $k < $start + $pageLength) {
				$data_ret[] = $ret[$k];
			}
			$k ++;
		}
		return response()-> json(array('data' => $data_ret, "draw" => 0,"recordsTotal"=> count($ret),"recordsFiltered"=>count($ret)));
	}
	
	public function getCouponList(Request $request){

		$coupon_collection = CouponUser::select('coupon_users.*')
		->where('coupon_users.ISSUED', '1')
		->orderBy('coupon_users.UPDTIME' , 'desc')
		->get();
		//$coupon_collection = CouponUser::all();
		$ret = [];
		foreach($coupon_collection as $item){
			$table_item = array(
				$item->ID,
				$item->user->USERID.($item->user->EXIT_YN=='Y'?'(탈퇴)':''),
				$item->coupon->DISPLAY_NAME,
				//intval($item->coupon->DISCOUNT),
				number_format($item->coupon->DISCOUNT , 0 , '.' , ',').'원' ,
				date("Y-m-d h:i:sa",$item->UPDTIME),
				($item->USE_DATE == '' || $item->USE_DATE == NULL) ? '' : date("Y-m-d h:i:sa",$item->USE_DATE)
			);

			$item->user->USERID = $item->user->USERID.($item->user->EXIT_YN=='Y'?'(탈퇴)':'');

			if($request->has("member_id")){
    			if(strrpos($item->user->USERID, $request->input("member_id")) === false)
    			{
    				continue;
    			}
    		}

			if($request->has("name")){
    			if(strrpos($item->coupon->DISPLAY_NAME, $request->input("name")) === false)
    			{
    				continue;
    			}
    		}


			if($request->has("issue_date_to")){
    			if($item->UPDTIME >= strtotime($request->input("issue_date_to")) + 86400)
    			{
    				continue;
    			}
    		}
    		
			if($request->has("issue_date_from")){
    			if($item->UPDTIME < strtotime($request->input("issue_date_from")))
    			{
    				continue;
    			}
    		}
    		
			if($request->has("create_date_to")){
    			if($item->USE_DATE >= strtotime($request->input("create_date_to")) + 86400)
    			{
    				continue;
    			}
    		}
    		
			if($request->has("create_date_from")){
    			if($item->USE_DATE < strtotime($request->input("create_date_from")))
    			{
    				continue;
    			}
    		}
    		
			$ret[] = $table_item;
		}

		$pageLength = intval($request->input('length'));
		$start = intval($request->input('start'));
		$data_ret = [];
		$k = 0;
		while(1) {
			if($k >= count($ret))
				break;
			if($k >= $start && $k < $start + $pageLength) {
				$data_ret[] = $ret[$k];
			}
			$k ++;
		}	

		return response()-> json(array('data' => $data_ret, "draw" => 0,"recordsTotal"=> count($ret),"recordsFiltered"=>count($ret)));
	}
	
	public function getUserList(Request $request){
		$user_collection = Admin::all();
		$ret = [];
		foreach($user_collection as $item){
			$table_item = array(
				$item->ID,
				$item->ADMIN_NAME,
				'<a href="'.url('admin/user/view?id=').$item->ID.'">'.$item->ADMIN_ID.'</a>',
				$item->STATUS == 0?"사용중":"사용안함",
				$item->TYPE == 0?"Master":"Admin",
				date("Y-m-d h:i:sa",$item->REGTIME),
				date("Y-m-d h:i:sa",$item->UPDTIME),
				date("Y-m-d h:i:sa",$item->LAST_LOGIN)
			);
			
			if($request->has("name")){
    			if(strrpos($item->ADMIN_NAME, $request->input("name")) === false)
    			{
    				continue;
    			}
    		}
			if($request->has("userid")){
    			if(strrpos($item->ADMIN_ID, $request->input("userid")) === false)
    			{
    				continue;
    			}
    		}
			if($request->has("yn_status")){
    			if(strrpos($item->STATUS, $request->input("yn_status")) === false && $request->input('yn_status') != "all")
    			{
    				continue;
    			}
    		}
			if($request->has("admin_type")){
    			if(strrpos($item->TYPE, $request->input("admin_type")) === false && $request->input('admin_type') != "all")
    			{
    				continue;
    			}
    		}
    		
			$ret[] = $table_item;
		}
		$pageLength = intval($request->input('length'));
		$start = intval($request->input('start'));
		$data_ret = [];
		$k = 0;
		while(1) {
			if($k >= count($ret)) break;
			if($k >= $start && $k < $start + $pageLength) { $data_ret[] = $ret[$k]; }
			$k ++;
		}
		return response()-> json(array('data' => $data_ret, "draw" => 0,"recordsTotal"=> count($ret),"recordsFiltered"=>count($ret)));
	}
	
	public function getPermissionList(Request $request){
		$permission_collection = Privilege::all();
		$ret = [];
		foreach($permission_collection as $item){
			$table_item = array(
				$item->ID,
				'<a href="'.url('admin/permission/view?id=').$item->ID.'">'.($item->ADMIN_TYPE == 1?"Master":"Admin").'</a>',
				$item->NAME,
				date("Y-m-d h:i:sa",$item->REGTIME),
				date("Y-m-d h:i:sa",$item->UPDTIME)
			);
			if($request->has("admin_type")){
				if($request->input("admin_type") != "all" && $item->ADMIN_TYPE != $request->input("admin_type")){
					continue;
				}
			}
			if($request->has("name") && $request->input("name") != "") {
				if(strrpos($item->NAME, $request->input("name")) === false) {
    				continue;
    			}				
			}
			$ret[] = $table_item;
		}

		$pageLength = intval($request->input('length'));
		$start = intval($request->input('start'));

		$data_ret = [];
		$k = 0;
		while(1) {
			if($k >= count($ret))
				break;
			if($k >= $start && $k < $start + $pageLength) {
				$data_ret[] = $ret[$k];
			}
			$k ++;
		}
		return response()-> json(array('data' => $data_ret, "draw" => 0,"recordsTotal"=> count($ret),"recordsFiltered"=>count($ret)));
	}
}
