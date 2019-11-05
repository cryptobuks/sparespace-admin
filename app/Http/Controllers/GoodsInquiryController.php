<?php

namespace App\Http\Controllers;

use App\Http\Models\RequestDetail;

use App\Http\Models\DetailGoods;

use App\Http\Models\Box;

use App\Http\Models\User;

use Illuminate\Http\Request;

use App\Http\Requests;

class GoodsInquiryController extends Controller
{
	function __construct(Request $request){
		$request->session()->set('active_menu', 2);
	}
	
    public function boxlist(Request $request){
    	$request->session()->set('active_sub_menu', 20);
		$request->session()->set('main_page_title', "박스관리");
    	return view('goodsinquiry/boxlist');
	}
	
	public function boxview(Request $request){
		$request->session()->set('active_sub_menu', 20);
		$request->session()->set('main_page_title', "박스관리");
		$box = Box::find($request->input("id"));
    	return view('goodsinquiry/boxview', array("item" => $box));
	}
	
	public function dobox(Request $request){
		$name = $request->input("name");
		$width = $request->input("width");
		$length= $request->input("length");
		$height = $request->input("height");
		$using_count =$request->input("using_count");
		$available_count = $request->input("available_count");
		$total_count = $request->input("total_count");
		$max_weight = $request->input("max_weight");
		$expect_qty = $request->input("expect_qty");
		$id = $request->input("id");

		$image1_url = ''; $filename1 = '';
		$image2_url = ''; $filename2 = '';

		if($_FILES['image1']['error'] == UPLOAD_ERR_OK) {
			$filename1 = $_FILES['image1']['name'];
			$fileName = rand(1, 999).date('m-d-Y_hia').$_FILES['image1']['name'];
			$dest = base_path('uploads/box/').$fileName;
			move_uploaded_file($_FILES['image1']['tmp_name'], $dest);
			$http_start = "http://";
			if($request->secure()) {
				$http_start = "https://";
			}
			$image1_url = $http_start.$request->getHttpHost().'/admin/uploads/box/'.$fileName;
		}

		if($_FILES['image2']['error'] == UPLOAD_ERR_OK) {
			$filename2 = $_FILES['image2']['name'];
			$fileName = rand(1, 999).date('m-d-Y_hia').$_FILES['image2']['name'];
			$dest = base_path('uploads/box/').$fileName;
			move_uploaded_file($_FILES['image2']['tmp_name'], $dest);
			$http_start = "http://";
			if($request->secure()) {
				$http_start = "https://";
			}
			$image2_url = $http_start.$request->getHttpHost().'/admin/uploads/box/'.$fileName;
		}

		if($id != ""){
			//if($filename1 == '')
			$update_data = array();
			$update_data['NAME'] = $name;
			$update_data['WIDTH'] = $width;
			$update_data['LENGTH'] = $length;
			$update_data['HEIGHT'] = $height;
			$update_data['USING_COUNT'] = $using_count;
			$update_data['AVAILABLE_COUNT'] = $available_count;
			$update_data['TOTAL_COUNT'] = $total_count;
			
			if($image1_url != '' && $image1_url != null) 
				$update_data['IMAGE1'] = $image1_url;
			if($image2_url != '' && $image2_url != null)
				$update_data['IMAGE2'] = $image2_url;

			$update_data['MAX_WEIGHT'] = $max_weight;
			$update_data['EXPECT_QTY'] = $expect_qty;
			if($filename1 != '' && $filename1 != null)
				$update_data['FILE_NAME1'] = $filename1;
			if($filename2 != '' && $filename2 != null)
				$update_data['FILE_NAME2'] = $filename2;
			$update_data['UPDTIME'] = time();
			$box = Box::where("ID", $id)->update($update_data);
			
		}else{
			$box = new Box();
			$box->NAME = $name;
			$box->WIDTH = $width;
			$box->LENGTH = $length;
			$box->HEIGHT = $height;
			$box->USING_COUNT = $using_count;
			$box->AVAILABLE_COUNT = $available_count;
			$box->TOTAL_COUNT = $total_count;			
			$box->MAX_WEIGHT = $max_weight;
			$box->EXPECT_QTY = $expect_qty;
			$box->IMAGE1 = $image1_url;
			$box->IMAGE2 = $image2_url;
			$box->FILE_NAME1 = $filename1;
			$box->FILE_NAME2 = $filename2;
			$box->REGTIME = time();
			$box->UPDTIME = time();
			$box->save();
		}

		return redirect('goodsinquiry/boxlist');
	}

	public function uploadtempimg(Request $request){
		$path = [];
		if($request->hasFile('files')) {
			foreach ($request->file('files') as $file) {
				$fileName = rand(1, 999).date('m-d-Y_hia').$file->getClientOriginalName();
				$file->move(base_path('uploads/goods/'), $fileName);
				$http_start = "http://";
				if($request->secure()) {
					$http_start = "https://";
				}
				$path[] = $http_start.$request->getHttpHost().'/admin/uploads/goods/'.$fileName;
			}
		}
		$names_str = $request->input("names");
		$images_str = $request->input("images");
		$remove = $request->input("remove_goods_ids");
		$server = $request->input("server");

		$names = explode(";;;;", $names_str);
		$images = explode(";;;;", $images_str);
		$remove_ids = explode(";;;;", $remove);
		$server_ids = explode(";;;;", $server);

		$id = $request->input("id");
		// add goods in detail goods
		for( $kkk = 0; $kkk < count($remove_ids); $kkk ++ )
		{
			$goods = DetailGoods::where("GOODSID" , $remove_ids[$kkk]);
			$goods->delete();
		}
		//
		$stack = 0;

		if($id != ""){
			$goods_count = 0;
			foreach($names as $key => $name) {
				if($server_ids[$key] != "0") {
					$goods = DetailGoods::where("GOODSID" , $server_ids[$key]);
					if($goods->first()->RESET_YN == 'N')
						$goods_count ++;
					$update_item = array();
					$update_item['GOODS_NAME'] = $name;
					$update_item['UPDTIME'] = time();
					if($images[$key] == "true") {
						$update_item['IMAGE'] = $path[$stack];
						$stack ++;
					}
					$update_item['STATUS'] = 1;
					$update_item['BOXID'] = $id;
					$goods->update($update_item);
				}
				else {
					$goods_count ++;
					$goods = new DetailGoods();
					$goods->GOODSID = $id.'T'.sprintf("%02d", intval($key)+1);
					$goods->GOODS_NAME = $name;
					$goods->REGTIME = time();
					$goods->UPDTIME = time();
					if($images[$key] == "true") {
						$goods->IMAGE = $path[$stack];
						$stack ++;
					}
					$goods->STATUS = 1;
					$goods->BOXID = $id;
					$goods->save();
				}
			}
			$selRequestDetail = RequestDetail::where("BOXID", $id)->first();
			if($selRequestDetail->MANAGE_STATUS == 1) {
				$box = RequestDetail::where("BOXID", $id)->update(array(
					"MANAGE_STATUS" => 4,
					"START_DATE" => date("Y-m-d"),
					"END_DATE" => date('Y-m-d', strtotime("+" . $selRequestDetail->STORAGE_MONTHS . " months")) ,
					"CHECKTIME" => time() ,
					"UPDTIME" => time() ,
					"GOODS_COUNT" => $goods_count
				));	
			}
			else {
				$box = RequestDetail::where("BOXID", $id)->update(array(
					"MANAGE_STATUS" => 4,
					"CHECKTIME" => time() ,
					"UPDTIME" => time() ,
					"GOODS_COUNT" => $goods_count
				));		
			}
		}
		return response()->json(['success'=> ""]);
	}
	
	public function goodsmanage(Request $request){
		$request->session()->set('active_sub_menu', 21);
		$request->session()->set('main_page_title', "물품관리");
		return view('goodsinquiry/goodsmanage', array(
			"form_title" => "물품관리"
		));
	}
	
	public function goodsview(Request $request){
		$box_id = $request->input("box_id");
		$request->session()->set('active_sub_menu', 21);
		$request->session()->set('main_page_title', "물품관리");
		$item = RequestDetail::where('BOXID', $box_id)->first();
		$status = array(0=>"", 1=>"검수중",2=>"검수불가",3=>"반송처리", 4=>"보관중");
		return view('goodsinquiry/goodsview', array(
			"item" => $item,
			"status_array" => $status
		));
	}
	
	public function doprocess(Request $request){
		$action = $request->input("action");
		$box_id = $request->input("box_id");
		if($action == "impossibility"){
			RequestDetail::where('BOXID', $box_id)->update(array("MANAGE_STATUS" => 2));
		}else if($action == "return"){
			$item = RequestDetail::where('BOXID', $box_id)->first();
			$param = array("data" => array("boxid" => $box_id , "userid" => $item->USER->ID));
			$param = json_encode($param);
			$host = $this->host_url."/service/cancel_box";	
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_URL, $host);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(    //<--- Added this code block
			        'Content-Type: application/json',
			        'Content-Length: ' . strlen($param))
			);
			$data = curl_exec($ch);
			$ret = json_decode($data , true);
			if($ret['status'] == 'success') {
				RequestDetail::where('BOXID', $box_id)->update(array("MANAGE_STATUS" => 3 , "UPDTIME" => time()));	
			}
			else {
				echo '<script>alert("'.$ret['errMsg'].'")</script>';
				return;
			}
		}else if($action == "check"){
			RequestDetail::where('BOXID', $box_id)->update(array("MANAGE_STATUS" => 1 , "UPDTIME" => time()));
		}
		return redirect('/goodsinquiry/view?box_id='.$box_id);
	}
	public function sendmessage(Request $request) {
		$box_id = $request->input("box_id");
		$item = RequestDetail::where('BOXID', $box_id)->first();
		$userinfo = User::where('ID' , $item['USER_ID'])->first();
		$content = array(
			"kr" => '회원님의 소중한 물품을 보관합니다. 보관물품은 [나의 물품확인]에서 확인할 수 있습니다.',
			"en" => '회원님의 소중한 물품을 보관합니다. 보관물품은 [나의 물품확인]에서 확인할 수 있습니다.'
		);
		$fields = array(
				'app_id' => "9dc5d130-ec07-4ed1-bab5-a9cabde7bc24",
				'include_player_ids' => array($userinfo['PLAYER_ID']),
				'data' => array("page" => "save_box"),
				'contents' => $content ,
				'headings' => array("kr" => "[보관시작 안내]" , "en" => "[보관시작 안내]")

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
		return response()->json(['status'=> "success"]);
	}

	public function getRefundInfo(Request $request) {
		$id = $request->input("id");
		$detail = RequestDetail::where('ID' , $id)->first();
		$detail->UPDTIME = date("Y-m-d h:i:sa", $detail->UPDTIME);
		return response()->json(array('status'=>'success' , 'data' => $detail));
	}
}



















