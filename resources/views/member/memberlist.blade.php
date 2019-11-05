@extends('member.layout')


@section('search_form')
<form class="horizontal-form">
	<div class="form-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">회원 ID</label>
					<input type="text" id="member_id" class="form-control">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Email</label>
					<input type="text" id="email" class="form-control">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">핸드폰 번호</label>
					<input type="text" id="mobile_no" class="form-control">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">상태</label>
					<div class="input-group">
						<div class="icheck-inline">
							<label>
							<input type="radio" name="status" class="icheck" checked value="all" id="radio_all">전체</label>
							<label>
							<input type="radio" name="status" class="icheck" value="PROGRESS" id="radio_progress">진행중</label>
							<label>
							<input type="radio" name="status" class="icheck" value="STORAGE" id="radio_storage">보관중</label>
							<label>
							<input type="radio" name="status" class="icheck" value="FINISH" id="radio_finish">종료</label>
							<label>
							<input type="radio" name="status" class="icheck" value="none" id="radio_none">없음</label>
							<input type="hidden" id="user_status" value="all"/>
						</div>
					</div>
				</div>
			</div>
		</div>
		<label class="control-label">가입일</label>
		<div class="row">					
			<div class="col-md-3">
				<div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
					<input type="text" id="processing_date_from" class="form-control form-filter input-sm" readonly name="order_date_from" placeholder="From">
					<span class="input-group-btn">
					<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
					</span>
				</div>
			</div>
			<div class="col-md-3">
				<div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
					<input type="text" id="processing_date_to" class="form-control form-filter input-sm" readonly name="order_date_from" placeholder="From">
					<span class="input-group-btn">
					<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
					</span>
				</div>
			</div>
			<div class="col-md-3">
				
			</div>
			<div class="col-md-3">
				<button id="search_btn" type="button" class="btn btn-primary btn-sm button_style">검색</button>
				<button type="button" id="reset_button" class="btn btn-default btn-sm">초기화</button>
			</div>
		</div>
	</div>
</form>
@endsection

@section('main_table')
<table class="table table-bordered" id="main-content-table">
  <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">회원ID</th>
      <th scope="col">Email</th>
      <th scope="col">핸드폰 번호</th>
      <th scope="col">주소</th>      
      <th scope="col">상태</th>
      <th scope="col">가입일</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
@endsection

@section('script')
<script type="text/javascript">
jQuery(document).ready(function(){
	$(".icheck").on('ifChecked', function(event){
		$("#user_status").val($(this).val());
	});
	$('.date-picker').datepicker({
        rtl: Metronic.isRTL(),
        autoclose: true
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
                "url": "{{url('api/getMemberList')}}"
            },
            "order": [
                [1, "asc"]
            ]
        }
    });

    grid.getTableWrapper().on('click', 'tr', function (e) {
		/*var user_id = $(this).children("td:first").html();
		location.href="{{url('/member/memberview?user_id=')}}" + user_id;*/
    });

    function updateTable(){
    	grid.setAjaxParam("member_id", jQuery("#member_id").val());
        grid.setAjaxParam("mobile_no", jQuery("#mobile_no").val());
        grid.setAjaxParam("email", jQuery("#email").val());
        grid.setAjaxParam("status", $("#user_status").val());
        grid.setAjaxParam("processing_date_from", jQuery("#processing_date_from").val());
        grid.setAjaxParam("processing_date_to", jQuery("#processing_date_to").val());
        grid.getDataTable().ajax.reload();
        grid.clearAjaxParams();
    }

    jQuery("#search_btn").click(function(){
		updateTable();
    });    

    jQuery("#reset_button").click(function(){
		$('#member_id').val('');
		$('#email').val('');
		$('#mobile_no').val('');
		$('#radio_all').iCheck('check');
		$('#radio_progress').iCheck('uncheck');
		$('#radio_storage').iCheck('uncheck');
		$('#radio_finish').iCheck('uncheck');
		$('#radio_none').iCheck('uncheck');
		$('#user_status').val('all');
		$('#processing_date_from').val('');
		$('#processing_date_to').val('');
    });    
});
</script>
@yield('condition_script')
@endsection