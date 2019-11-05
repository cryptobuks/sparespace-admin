<?php

namespace App\Http\Controllers;

use PhpParser\Node\Expr\Print_;

use App\Http\Models\Admin;

use App\Http\Models\Privilege;
use App\Http\Models\User;

use Illuminate\Http\Request;

use App\Http\Requests;

class AdminController extends Controller
{
	function __construct(Request $request){
		$request->session()->set('active_menu', 4);		
	}
	
	function login(Request $request){
		return view('login');
	}
	
	function logout(Request $request){
		$request->session()->forget('user');
		$request->session()->forget('permission');
		return redirect('/login');
	}
	
	function dologin(Request $request){
		$username = $request->input("username");
		$userpass = $request->input("userpass");
		
		if(trim($username) == ""){
			$request->session()->set("errorMsg","Please enter your username.");
			return redirect('/login');
		}
		
		if(trim($userpass) == ""){
			$request->session()->set("errorMsg","Please enter your password.");
			return redirect('/login');
		}
		
		$admin = Admin::where(array("ADMIN_ID" => $username, "ADMIN_PWD" => md5($userpass)))->first();
		if($admin != null){
			$request->session()->set("user", $admin);
			$request->session()->set("permission", $admin->permission);

			//var_dump($admin);
			//exit;
			//$admin->update(array("LAST_LOGIN" => time()));
			/*$push = PushNotification::where("ID", $id)->update(array(
				"TITLE" => $title,
				"CONTENTS" => $contents,
				"UPDTIME" => time()
			)); */
			$update_val = Admin::where('ID' , $admin->ID)->update(array(
				"LAST_LOGIN" => time()
			));
			return redirect('/');
		}else{
			$request->session()->set("errorMsg","Username or Password don't match");
			return redirect('/login')->withInput();
		}
	}
	
	public function userlist(Request $request){
		$request->session()->set('active_sub_menu', 40);
		$request->session()->set('main_page_title', "관리자 정보");
		return view('admin/user/list');
	}
	
	public function userview(Request $request){
		$permission_id = $request->input("p_id");
		$request->session()->set('active_sub_menu', 40);
		$request->session()->set('main_page_title', "관리자 정보");
		return view('admin/user/view',array(
			"item" => Admin::find($request->input("id")),
			"permissions" => Privilege::all(),
			"permission" =>(
				$request->has("p_id")?
				Privilege::find($permission_id): 
				($request->has('id')?Privilege::find(Admin::find($request->input("id"))->PRIVILEGE_ID): 
				Privilege::first()
				))
		));
	}
	
	public function doadmin(Request $request){
		$id = $request->input("ID");
		if($id != ""){
			if(trim($request->input("ADMIN_PWD")) != ""){
				Admin::where("ID", $id)->update(array(
					"UPDTIME" => time(),
					"ADMIN_NAME" => $request->input("ADMIN_NAME"),
					"ADMIN_ID" => $request->input("ADMIN_ID"),
					"ADMIN_PWD" => md5($request->input("ADMIN_PWD")),
					"STATUS" => $request->input("STATUS"),
					"TYPE" => $request->input("TYPE"),
					"PRIVILEGE_ID" => $request->input("PRIVILEGE_ID")
				));
			}else{
				Admin::where("ID", $id)->update(array(
					"UPDTIME" => time(),
					"ADMIN_NAME" => $request->input("ADMIN_NAME"),
					"ADMIN_ID" => $request->input("ADMIN_ID"),
					"STATUS" => $request->input("STATUS"),
					"TYPE" => $request->input("TYPE"),
					"PRIVILEGE_ID" => $request->input("PRIVILEGE_ID")
				));
			}			
		}else{
			$admin = new Admin();
			$admin->REGTIME = time();
			$admin->UPDTIME = time();
			$admin->ADMIN_NAME = $request->input("ADMIN_NAME");
			$admin->ADMIN_ID = $request->input("ADMIN_ID");
			$admin->ADMIN_PWD = md5($request->input("ADMIN_PWD"));
			$admin->STATUS = $request->input("STATUS");
			$admin->TYPE = $request->input("TYPE");
			$admin->PRIVILEGE_ID = $request->input("PRIVILEGE_ID");
			$admin->save();
		}
		
		return redirect('admin/user/list');
	}
	
	public function permissionlist(Request $request){
		$request->session()->set('active_sub_menu', 41);
		$request->session()->set('main_page_title', "권한설정");
		return view('admin/permission/list');
	}
	
	public function permissionview(Request $request){
		$id = $request->input("id");
		$request->session()->set('active_sub_menu', 41);
		$request->session()->set('main_page_title', "권한설정");
		return view('admin/permission/view', array(
			"item" => Privilege::find($id)
		));
	}
	
	public function dopermission(Request $request){
		$id = $request->input("ID");
		if($id != ""){
			$temp = array(
				"STATUS" => "N",
				"STATUS_1" => "N",
				"MEMBER" => "N",
				"MEMBER_1" => "N",
				"MEMBER_2" => "N",
				"MEMBER_3" => "N",
				"GOODS" => "N",
				"GOODS_1" => "N",
				"GOODS_2" => "N",
				"MANAGE" => "N",
				"MANAGE_1" => "N",
				"MANAGE_2" => "N",
				"MANAGE_3" => "N",
				"MANAGE_4" => "N",
				"MANAGE_5" => "N",
				"ADMIN" => "N",
				"ADMIN_1" => "N",
				"ADMIN_2" => "N"					
			);
			foreach($request->input() as $key => $item){
				if($key != "ADMIN_TYPE" && $key != "ID" && $key != "NAME" && $key != "DESC"){
					$temp[$key] = $item == "on"?"Y":"N";
				}else{
					$temp[$key] = $item;
				}
			}
			$permission = Privilege::where("ID", $id)->update($temp);
		}else{
			$permission = new Privilege();
			$permission->REGTIME = time();
			$permission->UPDTIME = time();
			foreach($request->input() as $key => $item){
				if($key != "ADMIN_TYPE" && $key != "NAME" && $key != "DESC"){
					$permission[$key] = $item == "on"?"Y":"N";
				}else{
					$permission[$key] = $item;
				}
				
			}
			$permission->save();
		}
		return redirect('/admin/permission/list');
	}
}
