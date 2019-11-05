<?php

namespace App\Http\Controllers;

use App\Http\Models\Banner;

use App\Http\Models\Faq;

use App\Http\Models\Notice;

use App\Http\Models\PushNotification;

use Illuminate\Http\Request;

use App\Http\Requests;

class AdministrativeController extends Controller
{
	function __construct(Request $request){
		$request->session()->set('active_menu', 3);		
	}
	
	public function getPushNotification(Request $request){
		$request->session()->set('active_sub_menu', 30);
		$request->session()->set('main_page_title', "푸시");
    	return view('administrative/push/list');
	}
	
	public function getPushView(Request $request){
		$id = $request->input("id");
		$notification = PushNotification::find($id);
		$request->session()->set('active_sub_menu', 30);
		$request->session()->set('main_page_title', "푸시");
    	return view('administrative/push/view', array(
    		"item" => $notification
    	));
	}
	
	public function dopush(Request $request){
		$id = $request->input("id");
		$title = $request->input("title");
		$contents = $request->input("contents");
		
		$admin_info = $request->session()->get("user");		

		if($id != ""){
			$push = PushNotification::where("ID", $id)->update(array(
				"TITLE" => $title,
				"CONTENTS" => $contents,
				"UPDTIME" => time() ,
				"ADMIN_ID" => $admin_info->ID
			));
		}else{
			$push = new PushNotification();
			$push->TITLE = $title;
			$push->CONTENTS = $contents;
			$push->REGTIME = time();
			$push->UPDTIME = time();
			$push->TYPE = 1;
			$push->ADMIN_ID = $admin_info->ID;
			$push->save();
		}

		$content      = array(
	        "en" => $contents ,
	        "kr" => $contents
	    );

	    $headings	  = array("kr" => "[".$title."]" , "en" => "[".$title."]");

		$fields = array(
	        'app_id' => "9dc5d130-ec07-4ed1-bab5-a9cabde7bc24",
	        'included_segments' => array(
	            'All'
	        ),
	        'data' => array("page" => "admin_push"),
	        'contents' => $content,
			'headings' => $headings 
	    );	

	    $fields = json_encode($fields);

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	        'Content-Type: application/json; charset=utf-8',
	        'Authorization: Basic NmM2MDE5ZjItZjRlYy00N2IwLTk0YjEtZWY1ZWM1ZGYyMmU0'
	    ));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($ch, CURLOPT_HEADER, FALSE);
	    curl_setopt($ch, CURLOPT_POST, TRUE);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    
	    //$response = curl_exec($ch);


	    curl_close($ch);

		return redirect('administrative/push/list');
	}
	
	public function getNotice(Request $request){
		$request->session()->set('active_sub_menu', 31);
		$request->session()->set('main_page_title', "공지사항");
    	return view('administrative/notice/list');
	}
	
	public function getNoticeView(Request $request){
		$id = $request->input("id");
		$notice = Notice::find($id);
		$request->session()->set('active_sub_menu', 31);
		$request->session()->set('main_page_title', "공지사항");
    	return view('administrative/notice/view', array(
    		"item" => $notice
    	));
	}
	
	public function donotice(Request $request){
		$admin_info = $request->session()->get("user");		
		$id = $request->input("id");
		$title = $request->input("title");
		$contents = $request->input("contents");
		
		if($id != ""){
			Notice::where("ID", $id)->update(array(
				"TITLE" => $title,
				"CONTENTS" => $contents,
				"UPDTIME" => time(),
				"ADMIN_ID" => $admin_info->ID
			));
		}else{
			$notice = new Notice();
			$notice->TITLE = $title;
			$notice->CONTENTS = $contents;
			$notice->REGTIME = time();
			$notice->UPDTIME = time();
			$notice->ADMIN_ID = $admin_info->ID;
			$notice->save();
		}
		return redirect('administrative/notice/list');
	}
	
	public function getFAQ(Request $request){
		$request->session()->set('active_sub_menu', 32);
		$request->session()->set('main_page_title', "FAQ");
    	return view('administrative/faq/list');
	}
	
	public function getFAQView(Request $request){
		$id = $request->input("id");
		$faq = Faq::find($id);
		$request->session()->set('active_sub_menu', 32);
		$request->session()->set('main_page_title', "FAQ");
    	return view('administrative/faq/view', array(
    		"item" => $faq
    	));
	}
	
	public function dofaq(Request $request){
		$admin_info = $request->session()->get("user");
		$id = $request->input("id");
		$title = $request->input("title");
		$contents = $request->input("contents");
		if($id != ""){
			Faq::where("ID", $id)->update(array(
				"TITLE" => $title,
				"CONTENTS" => $contents,
				"UPDTIME" => time(),
				"ADMIN_ID" => $admin_info->ID
			));

		}else{
			$notice = new Faq();
			$notice->TITLE = $title;
			$notice->CONTENTS = $contents;
			$notice->REGTIME = time();
			$notice->UPDTIME = time();
			$notice->ADMIN_ID = $admin_info->ID;
			$notice->save();
		}
		return redirect('administrative/faq/list');
	}
	
	public function getBanner(Request $request){
		$request->session()->set('active_sub_menu', 33);
		$request->session()->set('main_page_title', "배너관리");
    	return view('administrative/banner/list');
	}
	
	public function getBannerView(Request $request){
		$id = $request->input("id");
		$banner = Banner::find($id);
		$request->session()->set('active_sub_menu', 33);
		$request->session()->set('main_page_title', "배너관리");
    	return view('administrative/banner/view', array(
    		"item" => $banner
    	));
	}
	
	public function dobanner(Request $request){

		$id = $request->input("id");
		$title = $request->input("title");
		$contents = $request->input("contents");
		
		$filename = null;
		if ($request->file('image') != null && $request->file('image')->isValid()) {
			$fileName = rand(1, 999).date('m-d-Y_hia').$request->file('image')->getClientOriginalName();
		    $request->file('image')->move(base_path("/uploads/banner/"), $fileName);
		    $filename = "/uploads/banner/".$fileName;
		}
		if($id != ""){
			if($filename != null){
				Banner::where("ID", $id)->update(array(
					"TITLE" => $title,
					"CONTENTS" => $contents,
					"UPDTIME" => time(),
					"IMAGE" => $filename
				));
			}else{
				Banner::where("ID", $id)->update(array(
					"TITLE" => $title,
					"CONTENTS" => $contents,
					"UPDTIME" => time()
				));
			}			
		}else{
			$notice = new Banner();
			$notice->TITLE = $title;
			$notice->CONTENTS = $contents;
			$notice->REGTIME = time();
			$notice->UPDTIME= time();
			if($filename != null){
				$notice->IMAGE = $filename;
			}
			$notice->save();
		}
		return redirect('administrative/banner/list');
	}
	
	public function getCoupon(Request $request){
		$request->session()->set('active_sub_menu', 34);
		$request->session()->set('main_page_title', "쿠폰관리");
    	return view('administrative/coupon/list');
	}

	public function doRemove(Request $request) {
		if($request->input('type') == 'banner')  {
			$item = Banner::where('ID' , $request->input('id'));
			if($item != null) {
				$item->delete();
			}
		}
		else if($request->input('type') == 'faq') {
			$item = Faq::where('ID' , $request->input('id'));
			if($item != null) {
				$item->delete();
			}
		}
		else if($request->input('type') == 'notice') {
			$item = Notice::where('ID' , $request->input('id'));
			if($item != null) {
				$item->delete();
			}
		}
		else if($request->input('type') == 'push') {
			$item = PushNotification::where('ID' , $request->input('id'));
			if($item != null) {
				$item->delete();
			}
		}
		return response()->json(array("status" => 'success'));
	}
}
