@extends('member.layout')
@section('search_form')
<style>
    #trasnum_change_btn:hover {
        background-color: #1d1c1c !important;   
    }
    #trasnum_change_btn_in_finish:hover {
       background-color: #1d1c1c !important;    
    }
</style>
<form class="horizontal-form">
	<div class="form-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">회원ID</label>
					<input type="text" id="member_id" class="form-control" value="{{$user->USERID.($user->EXIT_YN=='Y'?'(탈퇴)':'')}}" readonly="">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Email</label>
					<input type="text" id="email" class="form-control" value="{{$user->USER_EMAIL.($sns_type == ''?'':(' / '.$sns_type))}}" readonly="">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">핸드폰 번호</label>
					<input type="text" id="member_id" class="form-control" value="{{$user->USER_CELL}}" readonly="">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">쿠폰</label>
					<input type="text" id="email" class="form-control" value="{{ $coupon_string }}" readonly="">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label">가입일</label>
					<input type="text" id="member_id" class="form-control" value="{{date("Y-m-d h:i:sa",$user->UPDTIME)}}" readonly="">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label">주소</label>
					<input type="text" id="member_id" class="form-control" value="{{$user->ADDR1}} {{$user->ADDR2}}" readonly="">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">생년월일(사업자 번호)</label>
					<input type="text" id="member_id" class="form-control" value="{{$user->BIRTH_NO}}" readonly="">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">최근 결제일</label>
					<input type="text" id="email" class="form-control" @if($payment_date != "") value="{{date("Y-m-d h:i:sa",$payment_date)}}" @endif readonly="">
				</div>
			</div>
		</div>
	</div>
</form>


@endsection

@section('main-content-tab')
<div>	
	<a id="btn-paymentlog" href="{{url('member/paymentlist?user_id='.$user->ID)}}">결제 로그보기</a>	
</div>
<div>
	<br>
	<h4>보관 및 신청내역</h4>	
	<br>
</div>
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link @if($action == 'PROGRESS') active @endif" href="{{url('member/memberview?user_id='.$user->ID.'&action=PROGRESS')}}">진행중</a>
  </li>
  <li class="nav-item ">
    <a class="nav-link @if($action == 'STORAGE') active @endif" href="{{url('member/memberview?user_id='.$user->ID.'&action=STORAGE')}}">보관중</a>
  </li>
  <li class="nav-item">
    <a class="nav-link @if($action == 'FINISH') active @endif" href="{{url('member/memberview?user_id='.$user->ID.'&action=FINISH')}}">종료</a>
  </li>

  @if($action == "PROGRESS")
  <button id="trasnum_change_btn" type="button" style="float:right; margin-right: 20px; background: #353333;" class="btn btn-info btn-sm button_style" data-method="change">운송번호수정</button>
  @endif
  @if($action == "FINISH")
  <button id="trasnum_change_btn_in_finish" type="button" style="float:right; margin-right: 20px; background: #353333;" class="btn btn-info btn-sm button_style" data-method="change">운송번호수정</button>
  @endif
</ul>
@endsection


@section('main_table')
<table class="table table-bordered" id="main-content-table">
  <thead>
  	@if($action == "PROGRESS")
    <tr>
      <th scope="col">No</th>
      <th scope="col">진행상세</th>
      <th scope="col">박스ID</th>
      <th scope="col">운송번호</th>
      <th scope="col">현황</th>      
      <th scope="col">처리일자</th>
    </tr>
    @endif
    @if($action == "STORAGE")
    <tr>
      <th scope="col">No</th>
      <th scope="col">박스ID</th>
      <th scope="col">보관중인 물품수</th>
      <th scope="col">보관시작일</th>
      <th scope="col">사용기간</th>      
      <th scope="col">자동연장</th>
      <th scope="col">비고</th>
    </tr>
    @endif
    @if($action == "FINISH")
    <tr>
      <th scope="col">No</th>
      <th scope="col">박스ID</th>
      <th scope="col">운송번호</th>
      <th scope="col">현황</th>
      <th scope="col">보관 시작일</th>
      <th scope="col">보관 종료일</th> 
      <th scope="col">사용기간</th>      
      <th scope="col">자동연장</th>
      <th scope="col">비고</th>
    </tr>
    @endif
  </thead>
  <tbody>
  	
  </tbody>
</table>

@endsection

@section('script')
<script type="text/javascript">
jQuery(document).ready(function(){
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
                [10, 20, -1],
                [10, 20, "All"]
            ],
            "pageLength": 10,
            "ajax": {
                "url": "{{url('/api/getAcceptStatusListByUserId')}}",
                "method":"post",
                "data":{
					"user_id": "{{$user->ID}}",
					"action" : "{{$action}}"
                }
            },
            "order": [
                [1, "asc"]
            ]
        }
    });	

    function updateTable(){
        grid.getDataTable().ajax.reload();
        grid.clearAjaxParams();
    }

    // trans number
    $('#trasnum_change_btn_in_finish').on('click' , function(e) {
        if($(this).attr('data-method') == 'change') {
            $(this).attr('data-method' , 'modify');
            $(this).html('운송번호저장');
            $("#main-content-table tbody tr").each(function(i) {
                var currentRow = $(this);
                var transNum = currentRow.find("td:eq(2)").text();
                var object = currentRow.find("td:eq(2)");
                object[0].innerHTML = "<input class='form-control' type='text' value='" + transNum + "'></input>";
            })    
        }     
        else {
             var trans_ids = [];
             $("#main-content-table tbody tr").each(function(i) {
                var currentRow = $(this);
                var input_box = currentRow.find("td:eq(2)").children();
                //console.log(transNum);
                trans_ids.push({
                    "id" : currentRow.find("td:eq(0)").text() , 
                    "trans_num" : input_box[0].value
                });
             });
             run_waitMe($('.page-content'), 2, 'bounce');            
             $.ajax({url: "{{url('/acceptstatus/change_transnum_in_detail')}}", 
                    type: 'POST',
                    data: {
                        trans_ids: trans_ids
                    } , 
                    success: function(result){  
                        $('.page-content').waitMe('hide'); 
                        if(result.status == 'success')             {
                            updateTable();
                            $('#trasnum_change_btn_in_finish').attr('data-method' , 'change');
                            $('#trasnum_change_btn_in_finish').html('운송번호수정');
                        }
                    }
             });
        }
    });

    $('#trasnum_change_btn').on('click' , function(e) {
        // #main-content-table
        if($(this).attr('data-method') == 'change') {
        
            $(this).attr('data-method' , 'modify');
            $(this).html('운송번호저장');

            $("#main-content-table tbody tr").each(function(i) {
                var currentRow = $(this);
                var transNum = currentRow.find("td:eq(3)").text();
                var object = currentRow.find("td:eq(3)");
                object[0].innerHTML = "<input class='form-control' type='text' value='" + transNum + "'></input>";
            })    
        }
        else {
            var trans_ids = [];
             $("#main-content-table tbody tr").each(function(i) {
                var currentRow = $(this);
                var input_box = currentRow.find("td:eq(3)").children();
                //console.log(transNum);
                trans_ids.push({
                    "id" : currentRow.find("td:eq(0)").text() , 
                    "trans_num" : input_box[0].value
                });
             });
             run_waitMe($('.page-content'), 2, 'bounce');            
             $.ajax({url: "{{url('/acceptstatus/change_transnum')}}", 
                    type: 'POST',
                    data: {
                        trans_ids: trans_ids
                    } , 
                    success: function(result){  
                        $('.page-content').waitMe('hide'); 
                        if(result.status == 'success')             {
                            updateTable();
                            $('#trasnum_change_btn').attr('data-method' , 'change');
                            $('#trasnum_change_btn').html('운송번호수정');
                        }
                    }
             });
        }
    });
});

function trans_click(transport_number) {
    window.open('http://www.ilogen.com/iLOGEN.Web.New/TRACE/TraceView.aspx?gubun=slipno&slipno=' + transport_number , null, 'height=660, width=730, status=no, resizable=no, scrollbars=yes, toolbar=no,location=no, menubar=no');
}

</script>
@yield('condition_script')
@endsection