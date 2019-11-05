<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = array(
        '/api/getAcceptStatusList',
    	'/api/getDetailGoods',
    	'/api/getMemberList',
    	'/api/getAcceptStatusListByUserId',
    	'/api/getPaymentLog',
    	'/api/getInquiryList',
    	'/member/doinquiry',
    	'/api/getBoxList',
    	'/goodsinquiry/dobox',
    	'/api/getGoodsList',
    	'/api/getRequestDetailList',
    	'/api/getPushNotificationList',
    	'/administrative/dopush',
    	'/api/getNoticeList',
    	'/administrative/donotice',
    	'/api/getFAQList',
    	'/administrative/dofaq',
    	'/api/getBannerList',
    	'/administrative/dobanner',
    	'/api/getCouponList',
    	'/api/getUserList',
    	'/api/getPermissionList',    
    	'/admin/dopermission',
    	'/admin/doadmin',
    	'/login',
		'/goodsinquiry/doprocess', 
		'/goodsinquiry/sendmessage',
        '/api/getAcceptStatusListForExcel',
        '/administrative/doRemove' ,
        '/api/getPaymentDetail' ,
        '/acceptstatus/change_status' ,
        '/acceptstatus/change_transnum' ,
        '/acceptstatus/change_transnum_in_detail',
        '/goodsinquiry/get_refund_info',
        '/acceptstatus/get_request_info' ,
        '/acceptstatus/get_box_info'
    );
}
