<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/login', 'AdminController@login');
Route::post('/login', 'AdminController@dologin');
Route::get('/logout','AdminController@logout');
	
Route::group(array('middleware' => 'auth'), function () {
	Route::get('/', 'AcceptStatusController@index');	
	
	//acceptstatus
	Route::get('/acceptstatus/progress', 'AcceptStatusController@progress');
	Route::get('/acceptstatus/edit/{id}', 'AcceptStatusController@edit');
	Route::post('/acceptstatus/edit', 'AcceptStatusController@postEdit');
	Route::get('/acceptstatus/storage', 'AcceptStatusController@storage');
	Route::get('/acceptstatus/finish', 'AcceptStatusController@finish');
	
	Route::post('/acceptstatus/change_status' , 'AcceptStatusController@ChangeStatus');
	Route::post('/acceptstatus/change_transnum' , 'AcceptStatusController@ChangeTransnum');
	Route::post('/acceptstatus/change_transnum_in_detail' , 'AcceptStatusController@ChangeTransnumInDetail');
	Route::post('/acceptstatus/get_request_info' , 'AcceptStatusController@getRequestInfo');
	Route::post('/acceptstatus/get_box_info' , 'AcceptStatusController@getBoxInfo');
	//member
	Route::get('/member/memberlist', 'MemberController@memberlist');
	Route::get('/member/memberview', 'MemberController@memberview');
	Route::get('/member/paymentlist', 'MemberController@paymentlist');
	Route::get('/member/inquirylist', 'MemberController@inquirylist');
	Route::get('/member/inquiryview', 'MemberController@inquiryview');
	Route::post('/member/doinquiry', 'MemberController@doinquiry');
	
	//goodsinquiry
	Route::get('/goodsinquiry/boxlist', 'GoodsInquiryController@boxlist');
	Route::get('/goodsinquiry/boxview', 'GoodsInquiryController@boxview');
	Route::post('/goodsinquiry/dobox','GoodsInquiryController@dobox');
	Route::post('/goodsinquiry/uploadtempimg','GoodsInquiryController@uploadtempimg');
	Route::get('/goodsinquiry/manage','GoodsInquiryController@goodsmanage');
	Route::get('/goodsinquiry/view','GoodsInquiryController@goodsview');
	Route::post('/goodsinquiry/doprocess','GoodsInquiryController@doprocess');
	Route::post('/goodsinquiry/sendmessage','GoodsInquiryController@sendmessage');
	
	Route::post('/goodsinquiry/get_refund_info' , 'GoodsInquiryController@getRefundInfo');	

	//administrative
	Route::get('/administrative/push/list','AdministrativeController@getPushNotification');
	Route::get('/administrative/push/view','AdministrativeController@getPushView');
	Route::post('/administrative/dopush', 'AdministrativeController@dopush');
	
	Route::get('/administrative/notice/list','AdministrativeController@getNotice');
	Route::get('/administrative/notice/view','AdministrativeController@getNoticeView');
	Route::post('/administrative/donotice', 'AdministrativeController@donotice');
	
	Route::get('/administrative/faq/list','AdministrativeController@getFAQ');
	Route::get('/administrative/faq/view','AdministrativeController@getFAQView');
	Route::post('/administrative/dofaq', 'AdministrativeController@dofaq');
	
	Route::get('/administrative/banner/list','AdministrativeController@getBanner');
	Route::get('/administrative/banner/view','AdministrativeController@getBannerView');
	Route::post('/administrative/dobanner', 'AdministrativeController@dobanner');
		
	Route::get('/administrative/coupon/list', 'AdministrativeController@getCoupon');
	
	//admin
	Route::get('/admin/user/list','AdminController@userlist');
	Route::get('/admin/user/view','AdminController@userview');
	Route::get('/admin/permission/list','AdminController@permissionlist');
	Route::get('/admin/permission/view','AdminController@permissionview');
	Route::post('/admin/dopermission','AdminController@dopermission');
	Route::post('/admin/doadmin','AdminController@doadmin');

	//ajax 
	Route::any('/api/getAcceptStatusList', 'AjaxController@getAcceptStatusList');
	Route::post('/api/getAcceptStatusListForExcel', 'AjaxController@getAcceptStatusListForExcel');


	Route::post('/api/getDetailGoods', 'AjaxController@getDetailGoods');
	Route::any('/api/getMemberList','AjaxController@getMemberList');
	Route::any('/api/getAcceptStatusListByUserId','AjaxController@getAcceptStatusListByUserId');
	Route::any('/api/getPaymentLog', 'AjaxController@getPaymentLog');
	Route::any('/api/getInquiryList', 'AjaxController@getInquiryList');
	Route::any('/api/getBoxList', 'AjaxController@getBoxList');
	Route::any('/api/getGoodsList', 'AjaxController@getGoodsList');
	Route::any('/api/getRequestDetailList', 'AjaxController@getRequestDetailList');

	Route::any('/api/getPushNotificationList', 'AjaxController@getPushNotificationList');
	Route::any('/api/getNoticeList', 'AjaxController@getNoticeList');
	Route::any('/api/getFAQList', 'AjaxController@getFAQList');
	Route::any('/api/getBannerList', 'AjaxController@getBannerList');
	Route::any('/api/getCouponList','AjaxController@getCouponList');
	Route::any('/api/getUserList', 'AjaxController@getUserList');
	Route::any('/api/getPermissionList','AjaxController@getPermissionList');

	Route::post('/administrative/doRemove', 'AdministrativeController@doRemove');
	Route::post('/api/getPaymentDetail', 'AjaxController@getPaymentDetail');
});



