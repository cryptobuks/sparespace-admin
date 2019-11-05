<!doctype html>
<html lang="en">
  <!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8"/>
		<title>Admin</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1" name="viewport"/>
		<meta content="" name="description"/>
		<meta content="" name="author"/>
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
		<link href="{{url('admin/pages/css/login.css')}}" rel="stylesheet" type="text/css"/>
		<!-- END THEME STYLES -->
		<link rel="shortcut icon" href="favicon.ico"/>
	</head>
	<!-- END HEAD -->
<body class="login">
<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
<div class="menu-toggler sidebar-toggler">
</div>
<!-- END SIDEBAR TOGGLER BUTTON -->
<!-- BEGIN LOGO -->
<div class="logo">
	<a href="index.html">
	<img src="{{url('assets/admin/layout/img/logo-big.png')}}" alt=""/>
	</a>
</div>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
	<!-- BEGIN LOGIN FORM -->
	<form class="login-form" action="{{url('/login')}}" method="post">
		<h3 class="form-title">Login</h3>
		@if(session()->has('errorMsg'))
		<div class="alert alert-danger">
			<button class="close" data-close="alert"></button>
			<span>
			{{session()->get('errorMsg')}}</span>
		</div>
		{{session()->forget('errorMsg')}}
		@endif
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Username</label>
			<input  value="{{old('username')}}" name="username" class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" />
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Password</label>
			<input value="{{old('userpass')}}" name="userpass" class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Password"/>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn btn-success btn-block uppercase">Login</button>			
		</div>		
	</form>
	<!-- END LOGIN FORM -->
</div>	

	<script src="{{url('global/plugins/jquery.min.js')}}" type="text/javascript"></script>
	<script src="{{url('global/plugins/jquery-migrate.min.js')}}" type="text/javascript"></script>
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
	});
	</script>
	@yield('script')
	<!-- END JAVASCRIPTS -->
  </body>
</html>