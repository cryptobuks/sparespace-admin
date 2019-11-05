@extends('layout')

@section('content')
<div class="tab-pane" id="tab_1">
	<div class="portlet box blue">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-gift"></i>검색
			</div>
			<div class="tools">
				<a href="javascript:;" class="collapse">
				</a>
				<a href="javascript:;" class="reload">
				</a>
			</div>
		</div>
		<div class="portlet-body form">
			<form class="horizontal-form">
				<div class="form-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">관리자명</label>
								<input type="text" id="name" class="form-control">
							</div>
						</div>									
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">Login ID</label>
								<input type="text" id="userid" class="form-control">
							</div>
						</div>	
					</div>
					<div class="row">
						<div class="col-md-6">							
							<div class="form-group">
								<label class="control-label">사용여부</label>
								<div class="input-group">
									<div class="icheck-inline">
										<label>
										<input type="radio" name="status" class="use_yn icheck" checked value="all" id="use_all">전체</label>
										<label>
										<input type="radio" name="status" class="use_yn icheck" value="0" id="use_y">사용중</label>
										<label>
										<input type="radio" name="status" class="use_yn icheck" value="1" id="use_n">사용안함</label>
										<input type="hidden" id="yn_status" value="all"/>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">관리자 유형</label>
								<div class="input-group">
									<div class="icheck-inline">
										<label>
										<input type="radio" name="status1" class="admin_type icheck" checked value="all" id="radio_all">전체</label>
										<label>
										<input type="radio" name="status1" class="admin_type icheck" value="0" id="radio_master">Master</label>
										<label>
										<input type="radio" name="status1" class="admin_type icheck" value="1" id="radio_admin">Admin</label>
										<input type="hidden" id="admin_type" value="all"/>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<button id="search_btn" type="button" class="btn btn-primary btn-sm button_style">검색</button>
							<button type="reset" class="btn btn-default btn-sm" id="reset_button">초기화</button>
						</div>
					</div>			
				</div>
			</form>			
		</div>
	</div>
</div>	
<div>
	<a href="{{url('admin/user/view')}}" class="btn btn-primary btn-sm">+ 추가</a>
</div>
<br>
<table class="table table-bordered" id="main-content-table">
  <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">관리자명</th>
      <th scope="col">Login ID</th>
      <th scope="col">사용여부</th>
      <th scope="col">관리자 유형</th> 
      <th scope="col">생성일</th>   
      <th scope="col">최근 수정일</th>  
	  <th scope="col">최근 로그인</th>  
    </tr>
  </thead>
  <tbody>
  	
  </tbody>
</table>
@endsection

@section('script')
<script type="text/javascript">
jQuery(document).ready(function(){

	$('.date-picker').datepicker({
        rtl: Metronic.isRTL(),
        autoclose: true
    });

	$('.use_yn').on('ifChecked', function(event){
		$("#yn_status").val($(this).val());
    });

	$('.admin_type').on('ifChecked', function(event){
		$("#admin_type").val($(this).val());
    });
	var grid = new Datatable();
    grid.init({
        src: jQuery("#main-content-table"),
        onSuccess: function (grid) {
        },
        onError: function (grid) {
           
        },
        onDataLoad: function(grid) {
        	
        },
        loadingMessage: 'Loading...',
        dataTable: { 
            "bStateSave": true,

            "lengthMenu": [
                [10, 20, 50 , 100],
                [10, 20, 50 , 100]
            ],
            "pageLength": 10,
            "ajax": {
                "url": "{{url('api/getUserList')}}"
            },
            "order": [
                [1, "asc"]
            ]
        }
    });
    grid.getTableWrapper().on('click', 'tr', function (e) {
		/*var id = $(this).children("td:first").html();
		if(isNaN(id) == false){
			location.href="{{url('admin/user/view?id=')}}" + id;
		}*/		
    });
    function updateTable(){
    	grid.setAjaxParam("name", jQuery("#name").val());
        grid.setAjaxParam("userid", jQuery("#userid").val());
        grid.setAjaxParam("yn_status", jQuery("#yn_status").val());
        grid.setAjaxParam("admin_type", jQuery("#admin_type").val());
        grid.getDataTable().ajax.reload();
        grid.clearAjaxParams();
    }
    jQuery("#search_btn").click(function(){
		updateTable();
    });    
    jQuery("#reset_button").click(function(){
		$('#name').val('');
		$('#userid').val('');
		$('#use_all').iCheck('check');
		$('#use_y').iCheck('uncheck');
		$('#use_n').iCheck('uncheck');
		$('#yn_status').val('all');
		$('#radio_all').iCheck('check');
		$('#radio_admin').iCheck('uncheck');
		$('#radio_master').iCheck('uncheck');
		$('#admin_type').val('all');
    });    

});
</script>
@endsection