<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Models\Reques;

use App\Http\Models\RequestDetail;

use App\Http\Models\FindTable;

class AcceptStatusController extends Controller
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

	function __construct(Request $request){
		$request->session()->set('active_menu', 0);
		$request->session()->set('active_sub_menu', 01);
		$request->session()->set('main_page_title', "신청 및 배송현황");
	}
	
	public function index(){
		return redirect('acceptstatus/progress');
	}

	public function edit(Request $request) {
		$reques = Reques::find($request->id);
		$reques = compact('reques'); 
		$reques['reques']['CONDITION'] = self::CONDITION_LABELS[$reques['reques']->CONDITION];
		return view('acceptstatus/edit', $reques);
	}

    public function progress(){
    	return view('acceptstatus/progress', array('action' => 'PROGRESS'));
	}
	
	public function storage(Request $request){		
		if($request->has("box_id")){
			return view('acceptstatus/storage', array('action' => 'STORAGE', 'box_id'=>$request->input("box_id")));
		}
		else{
			return view('acceptstatus/storage', array('action' => 'STORAGE'));
		}
	}
	
	public function finish(Request $request){
		return view('acceptstatus/finish', array('action' => 'FINISH'));	
	}

	public function postEdit(Request $request) {
		$reques = Reques::find($request->ID);
		$reques->fill($request->except(['_token', 'ID' , 'CONDITION']));
		$header = '상태변경';
		$message = '';
		if($request->input('SDETAIL') == "SHIPPING") {
			$reques->CONDITION = 'Custom_Delivery';
		}
		else if($request->input('SDETAIL') == "ARRANGE") {
			$reques->CONDITION = 'Returning_Standby';	
			$message = '물품 정리중입니다.';
		}
		else if($request->input('SDETAIL') == "COLLECT") {
			$reques->CONDITION = 'Returning';
			$message = '회송중입니다.';
		}
		else if($request->input('SDETAIL') == "CHECK"){
			$reques->CONDITION = 'Goods_Confirm';
			$message = '보내신 물품 검수중입니다.';
		}
		$reques->save();
		if($request->SDETAIL == "CHECK") {
			RequestDetail::where("REQUEST_ID", $request->ID)->update(array(
				"MANAGE_STATUS" => 1
			));
		}
		$reques = Reques::find($request->ID);
		$res = $this->sendmessage($header , $message , $reques->user->PLAYER_ID);
		return redirect('acceptstatus/progress');
	}

	public function sendmessage($header , $message , $player_ids) {
		/*$box_id = $request->input("box_id");
		$item = RequestDetail::where('BOXID', $box_id)->first();
		$userinfo = User::where('ID' , $item['USER_ID'])->first(); */
		$content = array(
			"kr" => $message,
			"en" => $message
		);
		$fields = array(
				'app_id' => "9dc5d130-ec07-4ed1-bab5-a9cabde7bc24",
				'include_player_ids' => $player_ids,
				'data' => array("page" => "save_box"),
				'contents' => $content ,
				'headings' => array("kr" => "[".$header."]" , "en" => "[".$header."]")

		);
		$fields = json_encode($fields);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	public function ChangeStatus(Request $request) {
		$sdetail = $request->input('SDETAIL');
		$condition = $request->input('CONDITION');
		$ids = $request->input('IDS');
		$player_ids = array();
		for ($i = 0; $i < count($ids); $i ++) {
			$reques = Reques::find($ids[$i]);
			//var_dump($reques->user->PLAYER_ID);
			//exit;
			$reques->SDETAIL = $sdetail;
			$reques->CONDITION =$condition;
			$reques->UPDTIME = time();
			$reques->save();
			if($sdetail == 'CHECK') {		
				RequestDetail::where("REQUEST_ID", $ids[$i])->update(array(
					"MANAGE_STATUS" => 1 ,
					"UPDTIME" => time()
				));	
			}
			if(!in_array($reques->user->PLAYER_ID, $player_ids)) {
				$player_ids[] = $reques->user->PLAYER_ID;
			}
		}
		$header = '상태변경';
		$message = '';
		if($request->input('SDETAIL') == "ARRANGE") {
			$message = '물품 정리중입니다.';
		}
		else if($request->input('SDETAIL') == "COLLECT") {
			$message = '회송중입니다.';
		}
		else if($request->input('SDETAIL') == "CHECK"){
			$message = '보내신 물품 검수중입니다.';
		}
		$res = $this->sendmessage($header , $message , $player_ids);
		return response()->json(array('status' => 'success'));
	}

	public function ChangeTransnum(Request $request) {
		$trans_ids = $request->input('trans_ids');
		if($request->input('__log')) {
			for( $i = 0; $i < count($trans_ids); $i ++ ) {
				$detail = FindTable::find($trans_ids[$i]['id']);
				if($detail->TRANS_NUMBER != $trans_ids[$i]['trans_num'])
					$detail->UPDTIME = time();
				$detail->TRANS_NUMBER = $trans_ids[$i]['trans_num'];
				$detail->save();	
			}
		}
		else {
			for( $i = 0; $i < count($trans_ids); $i ++ ) {
				$reques = Reques::find($trans_ids[$i]['id']);
				if($request->input('__type')) {
					if($reques->TRANS_NUMBER_A != $trans_ids[$i]['trans_num'])
						$reques->UPDTIME = time();							
					$reques->TRANS_NUMBER_A = $trans_ids[$i]['trans_num'];
				}
				else {
					if($reques->TRANS_NUMBER != $trans_ids[$i]['trans_num'])
						$reques->UPDTIME = time();							
					$reques->TRANS_NUMBER = $trans_ids[$i]['trans_num'];
				}
				$reques->save();
			}
		}
		return response()->json(array('status' => 'success'));	
	}

	public function ChangeTransnumInDetail(Request $request) {
		$trans_ids = $request->input('trans_ids');
		for( $i = 0; $i < count($trans_ids); $i ++ ) {
			$reques_detail = RequestDetail::find($trans_ids[$i]['id']);
			if($reques_detail != NULL) {
				if($reques_detail->TRANS_NUMBER != $trans_ids[$i]['trans_num'])
					$reques_detail->UPDTIME = time();
				$reques_detail->TRANS_NUMBER = $trans_ids[$i]['trans_num'];
				$reques_detail->save();		
			}
		}
		return response()->json(array('status' => 'success'));	
	}

	public function getRequestInfo(Request $request) {
		$id = $request->input("id");
		$detail = Reques::where('ID' , $id)->first();
		$detail->UPDTIME = date("Y-m-d h:i:sa", $detail->UPDTIME);
		$detail->STATUS = self::STATUS_LABELS[$detail->STATUS];
		$detail->SDETAIL = self::SDETAIL_LABELS[$detail->SDETAIL];
		$detail->CONDITION = self::CONDITION_LABELS[$detail->CONDITION];
		return response()->json(array('status'=>'success' , 'data' => $detail));
	}

	public function getBoxInfo(Request $request) {
		$boxid = $request->input('boxid');
		$detail = FindTable::where('BOXID' , $boxid)->get();
		$ret = array();
		foreach ($detail as $key => $value) {
			$table_item = array(
				"ID" => $value->ID , 
				"NOTE" => $value->NOTE, 
				"TRANS_NUMBER" => $value->TRANS_NUMBER , 
				"UPDTIME" => date("Y-m-d h:i:sa", $value->UPDTIME)
			);
			$ret[] = $table_item;
		}
		//$detail->UPDTIME = date("Y-m-d h:i:sa", $detail->UPDTIME);
		return response()->json(array('status'=>'success' , 'data' => $ret));
	}
}
