@extends('acceptstatus.layout')
@section('search_form')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
					<label class="control-label">핸드폰 번호</label>
					<input type="text" id="mobile_no" class="form-control">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">진행상세</label>
					{{-- <input type="text" id="progress_detail" class="form-control"> --}}
					<select class="form-control" id="progress_detail">
						<option value="">전체</option>
						<option value="SHIPPING">배송중</option>
						<option value="ARRANGE">물품정리중</option>
						<option value="COLLECT">회수중</option>
						<option value="CHECK">검수중</option>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">박스 ID</label>
					<input type="text" id="box_id" class="form-control">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">운송번호</label>
					<input type="text" id="shipping_no" class="form-control">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">현황</label>
					<select class="form-control" id="status">
						<option value="">전체</option>
						<option value="Custom_Delivery">고객전달중</option>
						<option value="RequestCancel_RA">신청취소</option>
						<option value="RequestCancel_RH">신청취소(위약금)</option>
						<option value="Returning_Standby">회송대기</option>
						<option value="Returning">회송중</option>
						<option value="Returning_Impropriety">회송불가능</option>
						<option value="Goods_Confirm">물품확인중</option>
						<option value="Finish">완료</option>
					</select>
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
</form>
@endsection

@section('main_table')
<table class="table table-bordered" id="main-content-table">
  <thead>
    <tr>
      <th scope="col">
      	<input type="checkbox" id="check_all" class="" />
      </th>
      <th scope="col">No</th>
      <th scope="col">회원 ID</th>
      <th scope="col">핸드폰 번호</th>
      <th scope="col">주소</th>
      <th scope="col">진행상세</th>
      <th scope="col">박스ID</th>
      <th scope="col">운송번호</th>
      <th scope="col">현황</th>
	  <th scope="col">처리일자</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<div id="track_modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 568px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title" id="track_title">상품추적</h4>
            </div>
            <div class="modal-body" style="width: 568px; height: 488px;">
                <iframe id="track_ifram_url" style="border: 0px; " src="" width="100%" height="100%"></iframe>
            </div>      
        </div>
    </div>
</div>

@endsection



