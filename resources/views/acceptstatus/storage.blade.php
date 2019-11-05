@extends('acceptstatus.layout')

@section('search_form')
<form action="#" class="horizontal-form">
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
					<label class="control-label">핸드폰 번호</label>
					<input type="text" id="mobile_no" class="form-control">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">박스 ID</label>
					<input type="text" id="box_id" class="form-control">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">운송번호</label>
					<input type="text" id="shipping_no" class="form-control">
				</div>
			</div>
		</div>
		<label class="control-label">처리일자</label>
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
					<input type="text" id="processing_date_to" class="form-control form-filter input-sm" readonly name="order_date_from" placeholder="To">
					<span class="input-group-btn">
					<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
					</span>
				</div>
			</div>
			<div class="col-md-3">
				
			</div>
			<div class="col-md-3">
				<button id="search_btn" type="button" class="btn btn-primary btn-sm button_style">검색</button>
				<button type="reset" class="btn btn-default btn-sm">초기화</button>
			</div>
		</div>
	</div>
	<input type="hidden" id="request_status" value="STORAGE"/>
</form>
@endsection

@section('main_table')
<table class="table table-bordered" id="main-content-table">
  <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">회원 ID</th>
      <th scope="col">핸드폰 번호</th>
      <th scope="col">주소</th>
      <th scope="col">박스</th>
      <th scope="col">보관중인 물품수</th>
      <th scope="col">보관 시작일</th>
      <th scope="col">사용기간</th>
      <th scope="col">자동연장</th>
      <th scope="col">비고</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
@endsection

@section('modal')
@if(!empty($box_id))
<div id="modal" class="modal fade" role="dialog" aria-hidden="true">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h4 class="modal-title">CONDITION DETAIL</h4>
		</div>
		<div class="modal-body">
			<table class="table table-bordered" id="condition-content-table">
			  <thead>
			    <tr>
			      <th scope="col">No</th>
			      <th scope="col">Note</th>
			      <th scope="col">Processing Date</th>
			      <th scope="col">Shipping No</th>
			      <th scope="col">Condition</th>
			    </tr>
			  </thead>
			  <tbody>			  	
			  </tbody>
			</table>
		</div>		
	</div>
</div>
</div>
@endif
@endsection

@section('condition_script')
@if(!empty($box_id))
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery("#modal").modal("show");
	
	var grid = new Datatable();

    grid.init({
        src: jQuery("#condition-content-table"),
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
                [10, 20, -1],
                [10, 20, "All"]
            ],
            "pageLength": 10,
            "ajax": {
                "url": "{{url('/api/getDetailGoods')}}",
                "method": "post",
                "data": {
                    "box_id": "{{$box_id}}"
                }
            },
            "order": [
                [1, "asc"]
            ]
        }
    });
});
</script>
@endif
@endsection

