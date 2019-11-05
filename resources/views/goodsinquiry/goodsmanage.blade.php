@extends('layout')

@section('content')
<div class="tab-pane" id="tab_1">
	<div class="portlet box blue">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-gift"></i>검색			</div>
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
								<label class="control-label">상태</label>
								<div class="input-group">
									<div class="icheck-inline">
										<label>
										<input type="radio" name="status" class="icheck" checked value="all" id="radio_all">전체</label>
										<label>
										<input type="radio" name="status" class="icheck" value="PROGRESS" id="radio_progress">진행중</label>
										<label>
										<input type="radio" name="status" class="icheck" value="STORAGE" id="radio_storage">보관중</label>
										<input type="hidden" id="goods_status" value="all"/>
									</div>
								</div>
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
						<div class="col-md-12">
							<button id="search_btn" type="button" class="btn btn-primary btn-sm button_style">검색</button>
							<button type="button" id="reset_button" class="btn btn-default btn-sm">초기화</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<table class="table table-bordered" id="main-content-table">
  <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">상태</th>
      <th scope="col">진행상세</th>
      <th scope="col">박스ID</th>
      <th scope="col">보관 물품수</th>      
      <th scope="col">회원 ID</th>
      <th scope="col">핸드폰 번호</th>
      <th scope="col">보관 종료일</th>
      <th scope="col">검수 완료일</th>
      <th scope="col">관리현황</th>
    </tr>
  </thead>
  <tbody>
  	
  </tbody>
</table>

<div id="modal" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title">물품현황</h4>
			</div>
			<div class="modal-body">
				<table class="table table-bordered" id="condition-content-table">
				  <thead>
				    <tr>
				      <th scope="col">No</th>
				      <th scope="col">관리현황</th>
				      <th scope="col">운송번호</th>
				      <th scope="col">비고</th>
				    </tr>
				  </thead>
				  <tbody>			  	
				  </tbody>
				</table>
			</div>		
		</div>
	</div>
</div>

<style>
#refund_table {
	font-family: arial, sans-serif;
  	border-collapse: collapse;
  	width: 100%;
}
#refund_table td ,#refund_table th {
	border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}
#refund_table tr:nth-child(even) {
	background-color: #dddddd;
}
#trasnum_change_btn:hover {
        background-color: #1d1c1c !important;   
 }
</style>

<div id="refund_modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 568px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title" id="refund_title">현황상세</h4>
            </div>
            <div id="refund_modal_body" class="modal-body" style="width: 568px; ">
            	<div class="row">
            		<div class="col-md-12">
            			<button id="trasnum_change_btn" type="button" style="float:right; margin-right: 20px; background: #353333;" class="btn btn-info btn-sm button_style" data-method="change">운송번호수정</button>
            		</div>
            	</div>
            	<div class="row" style="margin-top: 10px;">
            		<div class="col-md-12">
            			<table id="refund_table">
		            		<thead>
		            			<th>
		            				No
		            			</th>
		            			<th>
		            				현황
		            			</th>
		            			<th>
		            				현황 운송번호
		            			</th>
		            			<th>
		            				처리일자
		            			</th>
		            		</thead>
		            		<tbody id="refund_table_body">
		            			<tr>
		            				<td id="NO">1</td>
		            				<td>반송처리</td>
		            				<td id="TRANS_NUM">0000AB1</td>
		            				<td id="PROCESS_DATE">19/08/08 17:44:41</td>
		            			</tr>
		            		</tbody>
		            	</table>
            		</div>
            	</div>
            </div>      
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">

function drawModalTable (id) {
	//refund_table_body
	run_waitMe($('#refund_modal'), 2, 'bounce');            
	$('#refund_table_body').html("");
	var html = "";
	$.ajax(  {url: "{{url('/goodsinquiry/get_refund_info')}}", 
            type: 'POST',
            data: {
            	"id": id
            } , 
            success: function(result){   
            	console.log(result);
            	$('#refund_modal').waitMe('hide');      		
            	if(result.status == 'success') {
            		/*$('#NO').html(result.data.ID);
            		$('#TRANS_NUM').html(result.data.TRANS_NUMBER);
            		$('#PROCESS_DATE').html(result.data.UPDTIME);*/
            		var html = '<tr>' + 
		            				'<td id="NO">' + result.data.ID + '</td>' + 
		            				'<td>반송처리</td>' + 
		            				'<td id="TRANS_NUM"><a href="#" onclick="trans_click(\'' + result.data.TRANS_NUMBER + '\')">' + (result.data.TRANS_NUMBER == null?'':result.data.TRANS_NUMBER) + '</a></td>' + 
		            				'<td id="PROCESS_DATE">' + result.data.UPDTIME + '</td>' + 
		            			'</tr>';
            		$('#refund_table_body').html(html);
            	}
            }
   	});
}

function refund_click(id) {
	jQuery("#refund_modal").modal("show");
	drawModalTable(id);
}

jQuery(document).ready(function(){

	$(".icheck").on('ifChecked', function(event){
		$("#goods_status").val($(this).val());
	});

	$('#trasnum_change_btn').on('click' , function(e) {
        // #main-content-table
        if($(this).attr('data-method') == 'change') {
            $(this).attr('data-method' , 'modify');
            $(this).html('운송번호저장');
            $("#refund_table tbody tr").each(function(i) {
                var currentRow = $(this);
                var transNum = currentRow.find("td:eq(2)").text();
                var object = currentRow.find("td:eq(2)");
                object[0].innerHTML = "<input class='form-control' type='text' value='" + transNum + "'></input>";
            })    
        }
        else {
            var trans_ids = [];
             $("#refund_table tbody tr").each(function(i) {
                var currentRow = $(this);
                var input_box = currentRow.find("td:eq(2)").children();
                //console.log(transNum);
                trans_ids.push({
                    "id" : currentRow.find("td:eq(0)").text() , 
                    "trans_num" : input_box[0].value
                });
             });
             run_waitMe($('#refund_table'), 2, 'bounce');            
             $.ajax({url: "{{url('/acceptstatus/change_transnum_in_detail')}}", 
                    type: 'POST',
                    data: {
                        trans_ids: trans_ids
                    } , 
                    success: function(result){  
                        $('#refund_table').waitMe('hide'); 
                        if(result.status == 'success') {
                            $('#trasnum_change_btn').attr('data-method' , 'change');
                            $('#trasnum_change_btn').html('운송번호수정');
                            drawModalTable(trans_ids[0]['id']);
                        }
                    }
             });
        }
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
                "url": "{{url('api/getGoodsList')}}"
            },
            "order": [
                [1, "asc"]
            ]
        }
    });

    var grid1 = new Datatable();

    grid1.init({
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
                [10, 20, 50 , 100],
                [10, 20, 50 , 100]
            ],
            "pageLength": 10,
            "ajax": {
                "url": "{{url('api/getRequestDetailList')}}"
            },
            "order": [
                [1, "asc"]
            ]
        }
    });

    grid.getTableWrapper().on('click', '.goods_status', function (e) {
		var id = $(this).attr("boxid");
		
		jQuery("#modal").modal("show");

		grid1.setAjaxParam("box_id", id);
		grid1.getDataTable().ajax.reload();
    });
    
    function updateTable(){
    	grid.setAjaxParam("member_id", jQuery("#member_id").val());
        grid.setAjaxParam("mobile_no", jQuery("#mobile_no").val());
        grid.setAjaxParam("box_id", jQuery("#box_id").val());
        grid.setAjaxParam("status", jQuery("#goods_status").val());
        grid.getDataTable().ajax.reload();
        grid.clearAjaxParams();
    }

    jQuery("#search_btn").click(function(){
		updateTable();
    }); 
    jQuery("#reset_button").click(function(){
		jQuery("#member_id").val('');
		jQuery("#mobile_no").val('');
		jQuery("#box_id").val('');
		$('#goods_status').val('all');
		$('#radio_all').iCheck('check');
		$('#radio_progress').iCheck('uncheck');
		$('#radio_storage').iCheck('uncheck');
    }); 
});
function trans_click(transport_number) {
    window.open('http://www.ilogen.com/iLOGEN.Web.New/TRACE/TraceView.aspx?gubun=slipno&slipno=' + transport_number , null, 'height=660, width=730, status=no, resizable=no, scrollbars=yes, toolbar=no,location=no, menubar=no');
}
</script>
@endsection
