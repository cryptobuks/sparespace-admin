	<!doctype html>
<html lang="en">
  <!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8"/>
		<title>여유공간 관리자</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
		<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 
		<meta http-equiv="Content-Security-Policy" content="block-all-mixed-content"> 
		<meta content="width=device-width, initial-scale=1" name="viewport"/>
		<meta content="" name="description"/>
		<meta content="" name="author"/>
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<!-- BEGIN GLOBAL MANDATORY STYLES -->
		<link href="{{url('global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css"/>
		<link href="{{url('global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet" type="text/css"/>
		<link href="{{url('global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
		<link href="{{url('global/plugins/uniform/css/uniform.default.css')}}" rel="stylesheet" type="text/css"/>
		<link href="{{url('global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css"/>
		<!-- END GLOBAL MANDATORY STYLES -->
		<!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
		<link href="{{url('global/plugins/gritter/css/jquery.gritter.css')}}" rel="stylesheet" type="text/css"/>
		<link href="{{url('global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css')}}" rel="stylesheet" type="text/css"/>
		<link href="{{url('global/plugins/fullcalendar/fullcalendar.css')}}" rel="stylesheet" type="text/css"/>
		<link href="{{url('global/plugins/jqvmap/jqvmap/jqvmap.css')}}" rel="stylesheet" type="text/css"/>
		<link href="{{url('global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css"/>
		<!-- END PAGE LEVEL PLUGIN STYLES -->
		<!-- BEGIN PAGE STYLES -->
		<link href="{{url('admin/pages/css/tasks.css')}}" rel="stylesheet" type="text/css"/>
		<link href="{{url('global/plugins/icheck/skins/all.css')}}" rel="stylesheet"/>
		<!-- END PAGE STYLES -->
		<!-- BEGIN THEME STYLES -->
		<link href="{{url('global/css/components.css')}}" rel="stylesheet" type="text/css"/>
		<link href="{{url('global/css/plugins.css')}}" rel="stylesheet" type="text/css"/>
		<link href="{{url('admin/layout/css/layout.css')}}" rel="stylesheet" type="text/css"/>
		<link href="{{url('admin/layout/css/themes/default.css')}}" rel="stylesheet" type="text/css" id="style_color"/>
		<link href="{{url('admin/layout/css/custom.css')}}" rel="stylesheet" type="text/css"/>
		<link href="{{url('css/style.css')}}" rel="stylesheet" type="text/css"/>
		<link href="{{url('css/waitMe.css')}}" rel="stylesheet" type="text/css"/>
		<!-- END THEME STYLES -->
		<link rel="shortcut icon" href="favicon.ico"/>
		<script src="{{url('global/plugins/jquery.min.js')}}" type="text/javascript"></script>
		<script src="{{url('global/plugins/jquery-migrate.min.js')}}" type="text/javascript"></script>
		<script src="{{url('js/waitMe.js')}}" type="text/javascript"></script>
		<script src="{{url('js/lang.js')}}" type="text/javascript"></script>
		<script>
			function run_waitMe(el, num, effect)
			{
			    text = 'Please wait...'; fontSize = '';
			    
			    switch (num) {
			        case 1:
			            maxSize = '';
			            textPos = 'vertical';
			            break;
			        case 2:
			            text = '';
			            maxSize = 30;
			            textPos = 'vertical';
			            break;
			        case 3:
			            maxSize = 30;
			            textPos = 'horizontal';
			            fontSize = '18px';
			            break;
			    }

			    el.waitMe({
			        effect: effect,
			        text: text,
			        bg: 'rgba(189, 185, 193, 0.35)',
			        color: '#3d3d3d',
			        maxSize: maxSize,
			        waitTime: -1,
			        source: 'img.svg',
			        textPos: textPos,
			        fontSize: fontSize,
			        onClose: function(el) {}
			    });
			}
		</script>	
	</head>
	<!-- END HEAD -->

	<style>
		.button_style {
			padding-left: 20px;
    		padding-right: 20px;
    		padding-top: 6px;
    		padding-bottom: 6px;
		}
		.error {
			color: red;
		}
	</style>
	<body clas="page-header-fixed page-quick-sidebar-over-content page-sidebar-closed-hide-logo page-container-bg-solid">
	    <div class="page-header -i navbar navbar-fixed-top">
			<div class="page-header-inner">
				<div class="page-logo">
					<a href="index.html">
						<img src="{{url('admin/layout/img/logo.png')}}" alt="logo" class="logo-default" style="width: 86px;"/>
					</a>
					<div class="menu-toggler sidebar-toggler hide">
					</div>
				</div>
				<div class="top-menu">
					<ul class="nav navbar-nav pull-right">
						<li>
							<a>Hello, {{session()->get('user')->ADMIN_NAME}}</a>
						</li>
						<li>
							<a href="{{url('logout')}}">Log out</a>
						</li>
					</ul>
				</div>			
			</div>
		</div>

	    <div class="page-container" style="margin-top: 46px;">
		  	<!-- BEGIN SIDEBAR -->
			<div class="page-sidebar-wrapper">
			    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
			    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
			    <div class="page-sidebar navbar-collapse collapse">
			        <!-- BEGIN SIDEBAR MENU -->
			        <ul class="page-sidebar-menu" data-auto-scroll="true" data-slide-speed="200">
			            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
			            @if(session()->get('permission')->STATUS == "Y")
			            <li class="start @if(session('active_menu') == 0) active @endif">
			                <a>		                
				                <span class="title">접수현황</span>
				                <span class="selected"></span>
			                </a>
			                <ul class="sub-menu">
			                	@if(session()->get('permission')->STATUS_1 == "Y")
			                    <li class="@if(session('active_sub_menu') == 01) active @endif">
			                        <a href="{{url('/')}}">신청 및 배송현황</a>
			                    </li>   
			                    @endif                 
			                </ul>
			            </li>
			            @endif
			            @if(session()->get('permission')->MEMBER == "Y")
			            <li class="@if(session('active_menu') == 1) active @endif">
			                <a>		                
				                <span class="title">회원관리</span>
			                </a>
			                <ul class="sub-menu">
			                	@if(session()->get('permission')->MEMBER_1 == "Y")
			                    <li class="@if(session('active_sub_menu') == 10) active @endif">
			                        <a href="{{url('/member/memberlist')}}">회원정보</a>
			                    </li> 
			                    @endif
			                    @if(session()->get('permission')->MEMBER_2 == "Y")
			                    <li class="@if(session('active_sub_menu') == 11) active @endif">
			                        <a href="{{url('/member/paymentlist')}}">결제로그</a>
			                    </li> 
			                    @endif
			                    @if(session()->get('permission')->MEMBER_3 == "Y")
			                    <li class="@if(session('active_sub_menu') == 12) active @endif">
			                        <a href="{{url('/member/inquirylist')}}">1:1 문의</a>
			                    </li>                    
			                    @endif
			                </ul>
			            </li>
			            @endif
			            @if(session()->get('permission')->GOODS == "Y")
			            <li class="@if(session('active_menu') == 2) active @endif">
			                <a href="#">		                
				                <span class="title">물품조회</span>
				                <span class="selected"></span>
			                </a>
			                <ul class="sub-menu">
			                	@if(session()->get('permission')->GOODS_1 == "Y")
			                    <li class="@if(session('active_sub_menu') == 20) active @endif">
			                        <a href="{{url('/goodsinquiry/boxlist')}}">박스관리</a>
			                    </li>
			                    @endif
			                    @if(session()->get('permission')->GOODS_2 == "Y")
			                    <li class="@if(session('active_sub_menu') == 21) active @endif">
			                        <a href="{{url('/goodsinquiry/manage')}}">물품관리</a>
			                    </li>      
			                    @endif              
			                </ul>
			            </li>
			            @endif
			            @if(session()->get('permission')->MANAGE == "Y")
			            <li class="@if(session('active_menu') == 3) active @endif">
			                <a>		                
				                <span class="title">운영관리</span>
				                <span class="selected"></span>
			                </a>
			                <ul class="sub-menu">
			                	@if(session()->get('permission')->MANAGE_1 == "Y")
			                    <li class="@if(session('active_sub_menu') == 30) active @endif">
			                        <a href="{{url('administrative/push/list')}}">푸시</a>
			                    </li> 
			                    @endif
			                    @if(session()->get('permission')->MANAGE_2 == "Y")
			                    <li class="@if(session('active_sub_menu') == 31) active @endif">
			                        <a href="{{url('administrative/notice/list')}}">공지사항</a>
			                    </li>
			                    @endif
			                    @if(session()->get('permission')->MANAGE_3 == "Y")
			                    <li class="@if(session('active_sub_menu') == 32) active @endif">
			                        <a href="{{url('administrative/faq/list')}}">FAQ</a>
			                    </li>
			                    @endif
			                    <li class="@if(session('active_sub_menu') == 33) active @endif">
			                        <a href="{{url('administrative/banner/list')}}">배너관리</a>
			                    </li>              
			                    <li class="@if(session('active_sub_menu') == 34) active @endif">
			                        <a href="{{url('administrative/coupon/list')}}">쿠폰관리</a>
			                    </li>     
			                </ul>
			            </li>
			            @endif
			            @if(session()->get('permission')->ADMIN == "Y")
			            <li class="end @if(session('active_menu') == 4) active @endif">
			                <a>
				                <span class="title">관리자관리</span>
				                <span class="selected"></span>
			                </a>
			                <ul class="sub-menu">
			                	@if(session()->get('permission')->ADMIN_1 == "Y")
			                    <li class="@if(session('active_sub_menu') == 40) active @endif">
			                        <a href="{{url('admin/user/list')}}">관리자 정보</a>
			                    </li> 
			                    @endif
			                    @if(session()->get('permission')->ADMIN_2 == "Y")
			                    <li class="@if(session('active_sub_menu') == 41) active @endif">
			                        <a href="{{url('admin/permission/list')}}">권한설정</a>
			                    </li>  
			                    @endif
			                </ul>
			            </li>
			            @endif
			        </ul>
			        <!-- END SIDEBAR MENU -->
			    </div>
			</div>
			<!-- END SIDEBAR -->
			
			<div class="page-content-wrapper">
			    <div class="page-content">	        
			        <!-- BEGIN PAGE HEADER-->
			        <h3 class="page-title">
			            {{session('main_page_title')}}
			        </h3>
			        <!-- 
			        <div class="page-bar">
			            <ul class="page-breadcrumb">
			                <li>
			                    <i class="fa fa-home"></i>
			                    <a href="index.html">Home</a>
			                    <i class="fa fa-angle-right"></i>
			                </li>
			                <li>
			                    <a href="#">Page Layouts</a>
			                    <i class="fa fa-angle-right"></i>
			                </li>
			                <li>
			                    <a href="#">Blank Page</a>
			                </li>
			            </ul>
			            <div class="page-toolbar">
			                <div class="btn-group pull-right">
			                    <button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
			                    Actions <i class="fa fa-angle-down"></i>
			                    </button>
			                    <ul class="dropdown-menu pull-right" role="menu">
			                        <li>
			                            <a href="#">Action</a>
			                        </li>
			                        <li>
			                            <a href="#">Another action</a>
			                        </li>
			                        <li>
			                            <a href="#">Something else here</a>
			                        </li>
			                        <li class="divider">
			                        </li>
			                        <li>
			                            <a href="#">Separated link</a>
			                        </li>
			                    </ul>
			                </div>
			            </div>
			        </div>
			        
			        -->
			        <!-- END PAGE HEADER-->
			        <!-- BEGIN PAGE CONTENT-->
			        @yield('content')
			        <!-- END PAGE CONTENT-->
			    </div>
			</div>
			<!-- END CONTENT -->
		</div>
		
		<!-- BEGIN FOOTER -->
		<div class="page-footer">
		    <div class="page-footer-inner">
		         2019 &copy; 여유공간 관리자.
		    </div>
		    <div class="page-footer-tools">
		        <span class="go-top">
		        <i class="fa fa-angle-up"></i>
		        </span>
		    </div>
		</div>
	<!-- END FOOTER -->
	<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
	<script src="{{url('global/plugins/jquery-ui/jquery-ui.min.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/jquery.blockui.min.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/jquery.cokie.min.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/uniform/jquery.uniform.min.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
	<!-- END CORE PLUGINS -->
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<script src="{{url('global/plugins/jqvmap/jqvmap/jquery.vmap.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/flot/jquery.flot.min.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/flot/jquery.flot.resize.min.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/flot/jquery.flot.categories.min.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/jquery.pulsate.min.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/bootstrap-daterangepicker/moment.min.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/bootstrap-daterangepicker/daterangepicker.js')}}" type="text/javascript"></script>
	<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support -->
	<script src="{{url('global/plugins/fullcalendar/fullcalendar.min.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/jquery-easypiechart/jquery.easypiechart.min.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/jquery.sparkline.min.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/icheck/icheck.min.js')}}"></script>
	<!-- END PAGE LEVEL PLUGINS -->
	<!-- BEGIN PAGE LEVEL SCRIPTS -->
	<script src="{{url('global/scripts/metronic.js')}}" type="text/javascript"></script>
	<script src="{{url('admin/layout/scripts/layout.js')}}" type="text/javascript"></script>
	<script src="{{url('admin/layout/scripts/quick-sidebar.js')}}" type="text/javascript"></script>
	<script src="{{url('admin/layout/scripts/demo.js')}}" type="text/javascript"></script>
	<script src="{{url('admin/pages/scripts/index.js')}}" type="text/javascript"></script>
	<script src="{{url('admin/pages/scripts/tasks.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/datatables/media/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{url('global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js')}}"></script>
	<script src="{{url('global/scripts/datatable.js')}}"></script>
	<script src="{{url('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
	<script src="{{url('global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}"></script>
	<!-- END PAGE LEVEL SCRIPTS -->
	
	
	<script>
	jQuery(document).ready(function() {    
	   Metronic.init(); // init metronic core componets
	   Layout.init(); // init layout
	   //QuickSidebar.init(); // init quick sidebar
	   Demo.init(); // init demo features
	   /*
	   Index.init();   
	   Index.initDashboardDaterange();
	   Index.initJQVMAP(); // init index page's custom scripts
	    // init index page's custom scripts
	   Index.initCharts(); // init index page's custom scripts
	   Index.initChat();
	   Index.initMiniCharts();
	   Tasks.initDashboardWidget();
	   */
	});
	</script>
	@yield('script')
	<!-- END JAVASCRIPTS -->
  </body>
</html>