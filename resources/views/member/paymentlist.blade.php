@extends('member.layout')


@section('search_form')
<style>
	.row_price {
		margin-left: 0px;
    	margin-right: 0px;
    	padding-top: 5px;
    	padding-bottom: 5px;
	}
	.row_box_top {
	    margin-top: 10px;
	    font-size: 14px;
	    padding-top: 5px;
	    padding-bottom: 5px;
	    border-top: 1px solid #eee;
	    margin-left: 0px;
	    margin-right: 0px;
	}
	.row_box_bottom {
		margin-bottom: 5px;
	    font-size: 14px;
	    padding-top: 5px;
	    padding-bottom: 5px;
	    border-bottom: 1px solid #eee;
	    margin-left: 0px;
	    margin-right: 0px;
	}
	.margin_row {
		height: 10px;
	}
</style>
<form class="horizontal-form">
	<div class="form-body">
		<div class="row">
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
							<input type="hidden" id="payment_status" value="all"/>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
			</div>
		</div>
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
				<label class="control-label">승인일시</label>
			</div>
			<div class="col-md-6">
				<label class="control-label">금액</label>
			</div>
		</div>
		
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
				<input type="text" id="cost_from" class="form-control">
			</div>
			<div class="col-md-3">
				<input type="text" id="cost_to" class="form-control">
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
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
      <th scope="col">상태</th>
      <th scope="col">진행상세</th>
      <th scope="col">회원 ID</th>
      <th scope="col">핸드폰 번호</th>      
      <th scope="col">결제내용</th>
      <th scope="col">승인일시</th>
      <th scope="col">거래금액</th>
    </tr>
  </thead>
  <tbody>
  	
  </tbody>
</table>

<div id="payment_detail_modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 568px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title" id="payment_title">결제로그 상세(보관신청)</h4>
            </div>
            <div id="payment_modal_body" class="modal-body" style="width: 568px; ">
            </div>      
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
function number_format(number) {
	var num = parseFloat(number);
    if( isNaN(num) ) return "0";
    if(num == 0) return 0;
    var reg = /(^[+-]?\d+)(\d{3})/;
    var n = (num + '');
    while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');
    return n;
}
jQuery(document).ready(function(){
	
	$(".icheck").on('ifChecked', function(event){
		$("#payment_status").val($(this).val());
	});

	$('.date-picker').datepicker({
        rtl: Metronic.isRTL(),
        autoclose: true
    });
	
	var grid = new Datatable();
	var user_id = "{{ $user_id }}";

	if(user_id == "NULL") {
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
	                "url": "{{url('/api/getPaymentLog')}}"
	            },
	            "order": [
	                [1, "asc"]
	            ]
	        }
	    });
	}
	else {
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
	                "url": "{{url('/api/getPaymentLog?user_id='.$user_id)}}"
	            },
	            "order": [
	                [1, "asc"]
	            ]
	        }
	    });	
	}
    
    function updateTable(){
    	grid.setAjaxParam("member_id", jQuery("#member_id").val());
        grid.setAjaxParam("mobile_no", jQuery("#mobile_no").val());
        grid.setAjaxParam("status", $("#payment_status").val());
        grid.setAjaxParam("processing_date_from", jQuery("#processing_date_from").val());
        grid.setAjaxParam("processing_date_to", jQuery("#processing_date_to").val());
        grid.setAjaxParam("cost_from", jQuery("#cost_from").val());
        grid.setAjaxParam("cost_to", jQuery("#cost_to").val());
//        grid.setAjaxParam("user_id" , "{{ $user_id }}");

        grid.getDataTable().ajax.reload();
        grid.clearAjaxParams();
    }
    jQuery("#search_btn").click(function(){
		updateTable();
    });    
    jQuery("#reset_button").click(function(){
		$('#member_id').val('');
		$('#mobile_no').val('');
		$('#cost_from').val('');
		$('#cost_to').val('');
		$('#processing_date_from').val('');
		$('#processing_date_to').val('');

		$('#payment_status').val('all');
		$('#radio_all').iCheck('check');
		$('#radio_progress').iCheck('uncheck');
		$('#radio_storage').iCheck('uncheck');
		$('#radio_finish').iCheck('uncheck');
    });
});

function payment_modal(id) {
		run_waitMe($('.page-content'), 2, 'bounce');            
		$.ajax(  {url: "{{url('/api/getPaymentDetail')}}", 
            type: 'POST',
            data: {
            	"id": id
            } , 
            success: function(result){   
            	$('.page-content').waitMe('hide');      
            	//console.log(result);
            	if(result.status == 'success') {

            		$('#payment_modal_body').html('');

            		var detail_note = result.data.NOTE , detail;
            		detail = jQuery.parseJSON(detail_note);
            		console.log(detail);
            		//console.log(detail);
            		if(detail.type == 1) {
            			//<div class="row row_box_top"><div class="col-md-6">표준박스</div><div class="col-md-6">2box | 1개월</div></div>
            			//<div class="row row_box_bottom"><div class="col-md-6">금액</div><div class="col-md-6">20,000원</div></div>
            			var html = '' , i;
            			$('#payment_title').html('결제로그 상세(보관신청)');
            			for(i = 0; i < detail.box_list.length; i ++) {
            				html += '<div class="row row_box_top"><div class="col-md-6">' + detail.box_list[i]['name'] + '</div><div class="col-md-6">' + detail.box_list[i]['amount'] + 'box | ' + detail.box_list[i]['months'] + '개월</div></div>';
            				html += '<div class="row row_box_bottom"><div class="col-md-6">금액</div><div class="col-md-6">' + number_format(detail.box_list[i]['price']) + '원</div></div>';
            			}
            				html += '<div class="margin_row"></div>' +
			            	'<div class="row row_price">' +
			            		'<div class="col-md-6">여유공간 금액</div>' +
			            		'<div class="col-md-6">' + number_format(detail.space_cost) + '원</div>' +
			            	'</div>';
			            	html += '<div class="row row_price">' +
			            		'<div class="col-md-6">기본비용 금액</div>' + 
			            		'<div class="col-md-6">' + number_format(detail.main_cost) + '원</div>' + 
			            	'</div>';
			            	html += '<div class="row row_price">' +
				            		'<div class="col-md-6">할인쿠폰</div>' + 
				            		'<div class="col-md-6">' + detail.coupon_name + '</div>' + 
				            	'</div>';
				            if(detail.discount == undefined || detail.discount == 0 || detail.discount == '') 
				            	html += '<div class="row row_price">' +
	            						'<div class="col-md-6">할인</div>' + 
	            						'<div class="col-md-6">0원</div>' +
	            						'</div>';
				            else 
					            html += '<div class="row row_price">' +
	            						'<div class="col-md-6">할인</div>' + 
	            						'<div class="col-md-6">' + number_format(detail.discount) + '원</div>' +
	            						'</div>';
            				html += '<div class="row row_price" style="font-weight: 900;">' +
            						'<div class="col-md-6">총금액</div>' +
            						'<div class="col-md-6">' + number_format(detail.cost) + '원</div>' +
            						'</div>';

            				$('#payment_modal_body').html(html);

            		}
            		else if(detail.type == 2) {
            			//개별물건 찾기
            			var html = '' , i;
            			$('#payment_title').html('개별물건 찾기');

            			for(let  index = 0; index < detail.goods_list.length; index ++) {
            				html +=  '<div class="row row_price">' +
            						'<div class="col-md-3">상품명</div>' +
            						'<div class="col-md-3">' + detail.goods_list[index]['goods_name'] + '</div>' +
            						'<div class="col-md-3">상품아이디</div>' +
            						'<div class="col-md-3">' + detail.goods_list[index]['goods_id'] + '</div>' +
            					'</div>';	
            			}
            			

            			html +=  '<div class="row row_price">' +
			            		'<div class="col-md-6">박스 아이디</div>' +
			            		'<div class="col-md-6">' + detail.box_id + '</div>' +
			            	'</div>';
            			html += '<div class="row row_price" style="font-weight: 900;">' +
            						'<div class="col-md-6">서비스이용비용</div>' +
            						'<div class="col-md-6">' + number_format(detail.service_use_price) + '원</div>' +
            						'</div>';
            			$('#payment_modal_body').html(html);
            		}
            		else if(detail.type == 3) {
            			//기간내 재보관
            			var html = '' , i;
            			$('#payment_title').html('기간내 재보관');
            			html +=  '<div class="row row_price" style="font-weight: 900;">' +
			            		'<div class="col-md-6">박스 수량</div>' +
			            		'<div class="col-md-6">' + detail.box_count + '개</div>' +
			            	'</div>';

			            if(detail.combined_box_count[0] > 0)  {
			            	html +=  '<div class="row row_price">' +
			            		'<div class="col-md-6">표준 박스</div>' +
			            		'<div class="col-md-6">' + detail.combined_box_count[0] + '개</div>' +
			            	'</div>';	
			            }
			            if(detail.combined_box_count[1] > 0) {
			            	html +=  '<div class="row row_price">' +
			            		'<div class="col-md-6">의류 박스</div>' +
			            		'<div class="col-md-6">' + detail.combined_box_count[1] + '개</div>' +
			            	'</div>';		
			            }

            			html += '<div class="row row_price" style="font-weight: 900;">' +
            						'<div class="col-md-6">일반택배비용</div>' +
            						'<div class="col-md-6">' + number_format(detail.delivery_price) + '원</div>' +
            						'</div>';
            			$('#payment_modal_body').html(html);
            		}
            		else if(detail.type == 4) {
            			//자동결제
            			var html = '' , i;
            			$('#payment_title').html('자동연장신청');
            				html +=  '<div class="row row_price">' +
			            		'<div class="col-md-6">박스 아이디</div>' +
			            		'<div class="col-md-6">' + detail.box_id + '</div>' +
			            	'</div>';
            				html += '<div class="row row_price" style="font-weight: 900;">' +
            						'<div class="col-md-6">결제금액</div>' +
            						'<div class="col-md-6">' + number_format(detail.auto_pay_price) + '원</div>' +
            						'</div>';
            				$('#payment_modal_body').html(html);
            		}
            		else if(detail.type == 5) {
            			//환불(신청취소)
            			var html = '' , i;
            			$('#payment_title').html('환불(신청취소)');
            				html += '<div class="row row_price" style="font-weight: 900;">' +
            						'<div class="col-md-6">환불금액</div>' +
            						'<div class="col-md-6">' + number_format(detail.cost) + '원</div>' +
            						'</div>';
            			$('#payment_modal_body').html(html);	
            		}
            		else if(detail.type == 6) {
            			//환불(검수불가)
            			var html = '' , i;
            			$('#payment_title').html('환불(검수불가)');
            				html +=  '<div class="row row_price">' +
			            		'<div class="col-md-6">박스 아이디</div>' +
			            		'<div class="col-md-6">' + detail.boxid + '</div>' +
			            	'</div>';
            				html += '<div class="row row_price" style="font-weight: 900;">' +
            						'<div class="col-md-6">환불금액</div>' +
            						'<div class="col-md-6">' + number_format(detail.cost) + '원</div>' +
            						'</div>';
            				$('#payment_modal_body').html(html);
            		}
            		else if(detail.type == 7) {
            			var html = '' , i;
            			$('#payment_title').html('결제취소');
            				html += '<div class="row row_price" style="font-weight: 900;">' +
            						'<div class="col-md-6">결제취소금액</div>' +
            						'<div class="col-md-6">' + number_format(detail.cost) + '원</div>' +
            						'</div>';
            				$('#payment_modal_body').html(html);	
            		}
            		else if(detail.type == 8) {
            				var html = '' , i;
            				$('#payment_title').html('환불(회송불가)');
            				html += '<div class="row row_price" style="font-weight: 900;">' +
            						'<div class="col-md-6">환불금액</div>' +
            						'<div class="col-md-6">' + number_format(detail.cost) + '원</div>' +
            						'</div>';
            				$('#payment_modal_body').html(html);		
            		}
            		jQuery("#payment_detail_modal").modal("show");
            	}
            }
        }); 
}

</script>
@yield('condition_script')
@endsection