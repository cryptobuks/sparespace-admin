<?php

namespace App\Http\Controllers;

use App\Http\Models\Inquiry;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Models\User;
use App\Http\Models\PaymentLog;

class MemberController extends Controller
{
	function __construct(Request $request){
		$request->session()->set('active_menu', 1);
	}
	
    public function memberlist(Request $request){
    	$request->session()->set('active_sub_menu', 10);
    	$request->session()->set('main_page_title', "회원정보");
    	return view('member/memberlist', array("form_title"=>"검색"));
	}
	
	public function memberview(Request $request){
		$user_id = $request->input("user_id");
		$request->session()->set('active_sub_menu', 10);
    	$request->session()->set('main_page_title', "회원정보");

    	$payment = PaymentLog::where("USER_ID" , $user_id)->orderBy('REGTIME' , 'desc')->first();
    	
    	$payment_date = '';
    	if($payment != NULL)
    		$payment_date = $payment->REGTIME;
    	$sns_type = '';
    	$user = User::find($user_id);
    	$coupon_array = array();
    	foreach ($user->coupon_list as $key => $value) {
    		//var_dump($value->coupon);
    		if($value->ISSUED == 1 || $value->USED == 0) {
    			//var_dump($value->coupon->DESCRIPTION);
    			$coupon_array[] = $value->coupon->DESCRIPTION;
    		}
    	}
    	if($user->USER_TYPE == 1) 
    		$sns_type = 'google';
    	else if($user->USER_TYPE == 2)
    		$sns_type = 'facebook';
    	else if($user->USER_TYPE == 3)
    		$sns_type = 'naver';
    	else if($user->USER_TYPE == 4)
    		$sns_type = 'kakao';
    	else if($user->USER_TYPE == 5) 
    		$sns_type = '';

    	return view('member/memberview', array(
    		'action'=> $request->has("action")?$request->input("action"):"PROGRESS", 
    		'user' => $user, 
    		'sns_type' => $sns_type, 
    		'form_title' => '기본정보', 
    		'payment_date' => $payment_date,
    		'coupon_string' => implode("/", $coupon_array)
    	));
	}
	
	public function paymentlist(Request $request){
		$request->session()->set('active_sub_menu', 11);
    	$request->session()->set('main_page_title', "결제로그");
    	$user_id = $request->input("user_id");
		return view('member/paymentlist', array(
			"form_title" => "검색" ,
			"user_id" => $user_id
		));
	}
	
	public function inquirylist(Request $request){
		$request->session()->set('active_sub_menu', 12);
    	$request->session()->set('main_page_title', "1:1 문의");
    	
    	return view('member/inquirylist', array(
			"form_title" => "검색"
		));
	}
	
	public function inquiryview(Request $request){
		$inquiry_id = $request->input("id");
		$request->session()->set('active_sub_menu', 12);
    	$request->session()->set('main_page_title', "1:1 문의");
    	
    	$inquiry = Inquiry::where("ID" , $inquiry_id);
    	$inquiry->update(array("STATUS" => 1));

    	return view('member/inquiryview', array(
			"form_title" => "1:1 문의",
    		"item" => Inquiry::find($inquiry_id)
		));
	}
	
	public function doinquiry(Request $request){
		$id = $request->input("id");
		$answer = $request->input("answer");
		
		if($answer == '')
			$inquiry = Inquiry::where("ID", $id)->update(array("ANSWER" => $answer, "AWRTIME" => time()));
		else 
			$inquiry = Inquiry::where("ID", $id)->update(array("STATUS"=>2 , "ANSWER" => $answer , "AWRTIME" => time()));
		
		return redirect('member/inquirylist');
	}
}
