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
					<label class="control-label">내용</label>
					<input type="text" id="contents" class="form-control">
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-12">
						<label class="control-label">문의시간</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
							<input type="text" id="inquiry_time_from" class="form-control form-filter input-sm" readonly name="order_date_from" placeholder="From">
							<span class="input-group-btn">
							<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
							</span>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
							<input type="text" id="inquiry_time_to" class="form-control form-filter input-sm" readonly name="order_date_from" placeholder="To">
							<span class="input-group-btn">
							<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label">답변현황</label>
					<div class="input-group">
						<div class="icheck-inline">
							<label>
							<input type="radio" name="status" class="icheck" checked value="all" id="radio_all">전체</label>
							<label>
							<input type="radio" name="status" class="icheck" value="0" id="radio_unconfirmed">미확인</label>
							<label>
							<input type="radio" name="status" class="icheck" value="2" id="radio_completed">답변완료</label>
							<label>
							<input type="radio" name="status" class="icheck" value="1" id="radio_check">내용확인</label>
							<input type="hidden" id="inquiry_status" value="all"/>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<button id="search_btn" type="button" class="btn btn-primary btn-sm button_style">검색</button>
				<button type="button" class="btn btn-default btn-sm" id="reset_button">초기화</button>
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
      <th scope="col">회원 ID</th>
      <th scope="col">Email</th>
      <th scope="col">내용</th>      
      <th scope="col">문의시간</th>
      <th scope="col">답변현황</th>
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
		$("#inquiry_status").val($(this).val());
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
            "pageLength": 20,
            "ajax": {
                "url": "{{url('/api/getInquiryList')}}"
            },
            "order": [
                [1, "asc"]
            ]
        }
    });
    grid.getTableWrapper().on('click', 'tr', function (e) {
		/*var id = $(this).children("td:first").html();
		location.href="{{url('/member/inquiryview?id=')}}" + id; */
    });

    function updateTable(){
    	grid.setAjaxParam("member_id", jQuery("#member_id").val());
        grid.setAjaxParam("email", jQuery("#email").val());
        grid.setAjaxParam("contents", jQuery("#contents").val());
        grid.setAjaxParam("status", $("#inquiry_status").val());
        grid.setAjaxParam("inquiry_time_to", jQuery("#inquiry_time_to").val());
        grid.setAjaxParam("inquiry_time_from", jQuery("#inquiry_time_from").val());
        grid.getDataTable().ajax.reload();
        grid.clearAjaxParams();
    }

    jQuery("#search_btn").click(function(){
		updateTable();
    });    

    jQuery('#reset_button').click(function() {
    	$('#member_id').val('');
    	$('#email').val('');
    	$('#contents').val('');
    	$('#inquiry_time_to').val('');
    	$('#inquiry_time_from').val('');

    	$('#inquiry_status').val('all');
    	$('#radio_all').iCheck('check');
    	$('#radio_unconfirmed').iCheck('uncheck');
    	$('#radio_completed').iCheck('uncheck');
    	$('#radio_check').iCheck('uncheck');
    });
});
</script>
@yield('condition_script')
@endsection