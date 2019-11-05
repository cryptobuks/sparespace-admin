@extends('layout')

@section('content')

<style>
    #change_status:hover {
        background-color: #1d1c1c !important;
    }
    #trasnum_change_btn:hover {
        background-color: #1d1c1c !important;   
    }
    #trasnum_change_btn_in_finish:hover {
        background-color: #1d1c1c !important;      
    }
    #modal_trasnum_change_btn:hover {
        background-color: #1d1c1c !important;      
    }
    #logs_trasnum_change_btn:hover {
        background-color: #1d1c1c !important;         
    }
    #trans_table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }
    #trans_table td ,#trans_table th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }
    #trans_table tr:nth-child(even) {
        background-color: #dddddd;
    }

    #logs_table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }
    #logs_table td ,#logs_table th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }
    #logs_table tr:nth-child(even) {
        background-color: #dddddd;
    }

</style>
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
			@yield('search_form')
		</div>
	</div>
</div>
<?php
if($action == 'PROGRESS') {
?>
<div class="row">
    <div class="col-md-12" style="flex-direction: row; align-items: center; display: flex; font-size: 14px; font-weight: bold; margin-bottom: 20px;">
        <div class="first_label" style="margin-right: 10px;">
            아래 회원들의 진행상세값을 
        </div>
        <div class="select_box_1" style="margin-right: 5px;">
            <select class="form-control" id="method_change">
                            <option value="SHIPPING">배송중</option>
                            <option value="ARRANGE">물품정리중</option>
                            <option value="COLLECT">회수중</option>
                            <option value="CHECK">검수중</option>
            </select>
        </div>
        <div class="second_label" style="margin-right: 10px;">
            으로 , 현황을 
        </div>
        <div class="select_box_2" style="margin-right: 5px;">
            <select class="form-control" id="method_condition">
                <option value="Custom_Delivery">고객전달중</option>
                <option value="RequestCancel_RA">신청취소</option>
                <option value="RequestCancel_RH">신청취소(위약금)</option>
            </select>
        </div>
        <div class="third_label" style="margin-right: 15px;">
            (으)로 일괄변경하기 
        </div>
        <div class="button_container">
            <button id="change_status" type="button" class="btn btn-warning btn-sm button_style" style="background: #353333;">변경</button>
        </div>
    </div>
</div>
<?php
}
?>
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link @if($action == 'PROGRESS') active @endif" href="{{url('acceptstatus/progress')}}">진행중</a>
  </li>
  <li class="nav-item ">
    <a class="nav-link @if($action == 'STORAGE') active @endif" href="{{url('acceptstatus/storage')}}">보관중</a>
  </li>
  <li class="nav-item">
    <a class="nav-link @if($action == 'FINISH') active @endif" href="{{url('acceptstatus/finish')}}">종료</a>
  </li>
  <button id="excel_download_btn" type="button" style="float:right; margin-right: 40px; " class="btn btn-danger btn-sm button_style">엑셀 다운로드</button>
  @if($action == "PROGRESS")
  <button id="trasnum_change_btn" type="button" style="float:right; margin-right: 20px; background: #353333;" class="btn btn-info btn-sm button_style" data-method="change">운송번호수정</button>
  @endif
  @if($action == "FINISH")
  <button id="trasnum_change_btn_in_finish" type="button" style="float:right; margin-right: 20px; background: #353333;" class="btn btn-info btn-sm button_style" data-method="change">운송번호수정</button>
  @endif
</ul>

<div id="trans_modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 568px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title" id="refund_title">현황상세</h4>
            </div>
            <div id="trans_modal_body" class="modal-body" style="width: 568px; ">
                <div class="row">
                    <div class="col-md-12">
                        <button id="modal_trasnum_change_btn" type="button" style="float:right; margin-right: 20px; background: #353333;" class="btn btn-info btn-sm button_style" data-method="change">운송번호수정</button>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-md-12">
                        <table id="trans_table">
                            <thead>
                                <th>No</th>
                                <th>진행상세</th>
                                <th>현황</th>
                                <th>현황 운송번호</th>
                                <th>처리일자</th>
                            </thead>
                            <tbody id="trans_table_body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>      
        </div>
    </div>
</div>

<div id="logs_modal" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 568px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title" id="logs_title">현황상세</h4>
            </div>
            <div id="logs_modal_body" class="modal-body" style="width: 568px; max-height: 450px;">
                <div class="row">
                    <div class="col-md-12">
                        <button id="logs_trasnum_change_btn" type="button" style="float:right; margin-right: 20px; background: #353333;" class="btn btn-info btn-sm button_style" data-method="change">운송번호수정</button>
                    </div>
                    <input type="hidden" id="boxid_temp" value="" />
                </div>
                <div class="row" style="margin-top: 10px; margin-left: 5px; margin-right: 5px;">
                    <div class="col-md-12" style="height: 335px; overflow: auto; padding-left: 0px; padding-right: 0px;">
                        <table id="logs_table">
                            <thead>
                                <th>No</th>
                                <th>현황</th>
                                <th>현황 운송번호</th>
                                <th>처리일자</th>
                            </thead>
                            <tbody id="logs_table_body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>      
        </div>
    </div>
</div>

<script>
    var tab = '';
    <?php
    if($action == 'PROGRESS') {
    ?>
    tab = 'PROGRESS';
    <?php
    }
    ?>

    <?php
    if($action == 'STORAGE') {
    ?>
    tab = 'STORAGE';
    <?php
    }
    ?>

    <?php
    if($action == 'FINISH') {
    ?>
    tab = 'FINISH';
    <?php
    }
    ?>
</script>
@yield('main_table')
@yield('modal')

@endsection

@section('script')
<script type="text/javascript" src="//unpkg.com/xlsx/dist/xlsx.full.min.js"></script>

<script type="text/javascript">

function drawLogTable(boxid) {
    run_waitMe($('#logs_modal'), 2, 'bounce');            
    $('#logs_table_body').html("");
    var html = "";
    $.ajax(  { url: "{{url('/acceptstatus/get_box_info')}}", 
            type: 'POST',
            data: {
                "boxid": boxid
            } , 
            success: function(result){   
                $('#logs_modal').waitMe('hide');              
                if(result.status == 'success') {
                    var html = '';
                    for(i = 0; i < result.data.length; i ++) {
                        html += '<tr>' + 
                                    '<td>' + result.data[i].ID + '</td>' + 
                                    '<td>' + result.data[i].NOTE + '</td>' + 
                                    '<td><a href="#" onclick="trans_click(\'' + result.data[i].TRANS_NUMBER + '\')">' + ((result.data[i].TRANS_NUMBER==null)?'':result.data[i].TRANS_NUMBER) + '</a></td>' + 
                                    '<td>' + result.data[i].UPDTIME + '</td>' + 
                                '</tr>';
                    }
                    $('#logs_table_body').html(html);
                }
            }
    });
}

function drawModalTable (id) {
    run_waitMe($('#trans_modal'), 2, 'bounce');            
    $('#trans_table_body').html("");
    var html = "";
    $.ajax(  {url: "{{url('/acceptstatus/get_request_info')}}", 
            type: 'POST',
            data: {
                "id": id
            } , 
            success: function(result){   
                console.log(result);
                $('#trans_modal').waitMe('hide');              
                if(result.status == 'success') {
                    /*$('#NO').html(result.data.ID);
                    $('#TRANS_NUM').html(result.data.TRANS_NUMBER);
                    $('#PROCESS_DATE').html(result.data.UPDTIME);*/
                    var html = '<tr>' + 
                                    '<td>' + result.data.ID + '</td>' + 
                                    '<td>' + result.data.SDETAIL + '</td>' + 
                                    '<td>' + result.data.CONDITION + '</td>' + 
                                    '<td><a href="#" onclick="trans_click(\'' + result.data.TRANS_NUMBER_A + '\')">' + ((result.data.TRANS_NUMBER_A==null)?'':result.data.TRANS_NUMBER_A) + '</a></td>' + 
                                    '<td>' + result.data.UPDTIME + '</td>' + 
                                '</tr>';
                    $('#trans_table_body').html(html);
                }
            }
    });
}

function add_trans_click(id) {
    jQuery("#trans_modal").modal("show");
    drawModalTable(id);
}

function logs_click(boxid) {
    jQuery("#logs_modal").modal("show");
    $('#boxid_temp').val(boxid);
    drawLogTable(boxid);
}

jQuery(document).ready(function(){
    $( ".date-picker" ).datepicker();

    $('#logs_trasnum_change_btn').on('click' , function(e) {
        if($(this).attr('data-method') == 'change') {
            $(this).attr('data-method' , 'modify');
            $(this).html('운송번호저장');
            $("#logs_table tbody tr").each(function(i) {
                var currentRow = $(this);
                var transNum = currentRow.find("td:eq(2)").text();
                var object = currentRow.find("td:eq(2)");
                object[0].innerHTML = "<input class='form-control' type='text' value='" + transNum + "'></input>";
            })    
        }
        else {
            var trans_ids = [];
             $("#logs_table tbody tr").each(function(i) {
                var currentRow = $(this);
                var input_box = currentRow.find("td:eq(2)").children();
                //console.log(transNum);
                trans_ids.push({
                    "id" : currentRow.find("td:eq(0)").text() , 
                    "trans_num" : input_box[0].value
                });
             });
             run_waitMe($('#logs_table'), 2, 'bounce');            
             $.ajax({url: "{{url('/acceptstatus/change_transnum')}}", 
                    type: 'POST',
                    data: {
                        trans_ids : trans_ids ,
                        __log : 1
                    } , 
                    success: function(result){  
                        $('#logs_table').waitMe('hide'); 
                        if(result.status == 'success') {
                            $('#logs_trasnum_change_btn').attr('data-method' , 'change');
                            $('#logs_trasnum_change_btn').html('운송번호수정');
                            drawLogTable($('#boxid_temp').val());
                        }
                    }
             });            
        }
    })

    $('#modal_trasnum_change_btn').on('click' , function(e) {
        if($(this).attr('data-method') == 'change') {
            $(this).attr('data-method' , 'modify');
            $(this).html('운송번호저장');
            $("#trans_table tbody tr").each(function(i) {
                var currentRow = $(this);
                var transNum = currentRow.find("td:eq(3)").text();
                var object = currentRow.find("td:eq(3)");
                object[0].innerHTML = "<input class='form-control' type='text' value='" + transNum + "'></input>";
            })    
        }
        else {
            var trans_ids = [];
             $("#trans_table tbody tr").each(function(i) {
                var currentRow = $(this);
                var input_box = currentRow.find("td:eq(3)").children();
                //console.log(transNum);
                trans_ids.push({
                    "id" : currentRow.find("td:eq(0)").text() , 
                    "trans_num" : input_box[0].value
                });
             });
             run_waitMe($('#trans_table'), 2, 'bounce');            
             $.ajax({url: "{{url('/acceptstatus/change_transnum')}}", 
                    type: 'POST',
                    data: {
                        trans_ids : trans_ids , 
                        __type : 1
                    } , 
                    success: function(result){  
                        $('#trans_table').waitMe('hide'); 
                        if(result.status == 'success') {
                            $('#modal_trasnum_change_btn').attr('data-method' , 'change');
                            $('#modal_trasnum_change_btn').html('운송번호수정');
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
            $('.progress_list').on('change' , function(e) {

                var all_checked = true , all_unchecked = true;

                $('.progress_list').each(function (i) {
                    if($(this).prop('checked')) {
                        all_unchecked = false;
                    }
                    if(!$(this).prop('checked')) {
                        all_checked = false;
                    }
                });             

                if(all_checked) {
                    $('#check_all').parent().addClass('checked');
                    $('#check_all').prop('checked' , true);
                }
                else  {
                    $('#check_all').parent().removeClass('checked');
                    $('#check_all').prop('checked' , false);   
                }
            });
        },
        loadingMessage: 'Loading...',
        dataTable: { 
            "bStateSave": true,
            "lengthMenu": [
                [10, 20 , 50 , 100],
                [10, 20 , 50 , 100]
            ],
            "pageLength": 20,
            "ajax": {
                "url": "{{url('api/getAcceptStatusList')}}?request_status={{$action}}"
            },
            "order": [
                [1, "asc"]
            ]
        }
    }); 
// trans number
    $('#trasnum_change_btn_in_finish').on('click' , function(e) {
        if($(this).attr('data-method') == 'change') {
            $(this).attr('data-method' , 'modify');
            $(this).html('운송번호저장');
            $("#main-content-table tbody tr").each(function(i) {
                var currentRow = $(this);
                var transNum = currentRow.find("td:eq(5)").text();
                var object = currentRow.find("td:eq(5)");
                object[0].innerHTML = "<input class='form-control' type='text' value='" + transNum + "'></input>";
            })    
        }     
        else {
             var trans_ids = [];
             $("#main-content-table tbody tr").each(function(i) {
                var currentRow = $(this);
                var input_box = currentRow.find("td:eq(5)").children();
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
                var transNum = currentRow.find("td:eq(7)").text();
                var object = currentRow.find("td:eq(7)");
                object[0].innerHTML = "<input class='form-control' type='text' value='" + transNum + "'></input>";
            })    
        }
        else {
            var trans_ids = [];
             $("#main-content-table tbody tr").each(function(i) {
                var currentRow = $(this);
                var input_box = currentRow.find("td:eq(7)").children();
                //console.log(transNum);
                trans_ids.push({
                    "id" : currentRow.find("td:eq(1)").text() , 
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

// change status code
    $('#method_change').on('change' , function(e) {
        if($('#method_change').val() == 'SHIPPING') {
            $('#method_condition').html('<option value="Custom_Delivery">고객전달중</option>' + 
                '<option value="RequestCancel_RA">신청취소</option>' + 
                '<option value="RequestCancel_RH">신청취소(위약금)</option>');
        }
        else if($('#method_change').val() == 'ARRANGE') {
            $('#method_condition').html('<option value="Returning_Standby">회송대기</option>' + 
                '<option value="Returning_Impropriety">회송불가</option>');
        }
        else if($('#method_change').val() == 'COLLECT') {
            $('#method_condition').html('<option value="Returning">회송중</option><option value="Returning_Standby">회송대기</option>');   
        }
        else if($('#method_change').val() == 'CHECK') {
            $('#method_condition').html('<option value="Goods_Confirm">물품확인중</option>');
        }
    });

    $('#check_all').on('click' , function(e) {
        if($('#check_all').prop('checked')) {
            $('.progress_list').each(function(i) {
                $(this).parent().addClass('checked');
                $(this).prop('checked',true);
            });
        } 
        else {
            $('.progress_list').each(function(i) {
                $(this).parent().removeClass('checked');
                $(this).prop('checked',false);
            });  
        }
    })

    $('#change_status').on('click' , function(e) {

        if(!confirm(Message.status_change))        {
            return;
        }

        var SDETAIL = $('#method_change').val();
        var CONDITION = $('#method_condition').val();
        var IDS = [];
        $('.progress_list').each(function (i) {
            if($(this).prop('checked')) {
               IDS.push($(this).data('id'));
            }
        }); 
        if(IDS.length == 0) {
            alert(Message.no_selected);
            return;
        }
        run_waitMe($('.page-content'), 2, 'bounce');            
        $.ajax({url: "{{url('/acceptstatus/change_status')}}", 
            type: 'POST',
            data: {
                SDETAIL: SDETAIL , 
                CONDITION: CONDITION , 
                IDS: IDS
            } , 
            success: function(result){  
                $('.page-content').waitMe('hide'); 
                if(result.status == 'success')             {
                    updateTable();
                }
            }
        });
    });
//
    function getCurrentDate() {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        today = yyyy + mm + dd;
        return today;
    }

    function updateTable(){
    	grid.setAjaxParam("member_id", jQuery("#member_id").val());
        grid.setAjaxParam("mobile_no", jQuery("#mobile_no").val());
        grid.setAjaxParam("progress_detail", jQuery("#progress_detail").val());
        grid.setAjaxParam("box_id", jQuery("#box_id").val());
        grid.setAjaxParam("shipping_no", jQuery("#shipping_no").val());
        grid.setAjaxParam("status", jQuery("#status").val());
        grid.setAjaxParam("processing_date_from", jQuery("#processing_date_from").val());
        grid.setAjaxParam("processing_date_to", jQuery("#processing_date_to").val());
        grid.setAjaxParam("start_date_from" , jQuery("#start_date_from").val());
        grid.setAjaxParam("start_date_to" , jQuery("#start_date_to").val());
        grid.setAjaxParam("end_date_from" , jQuery("#end_date_from").val());
        grid.setAjaxParam("end_date_to" , jQuery("#end_date_to").val());
        grid.getDataTable().ajax.reload();
        grid.clearAjaxParams();
    }
    jQuery("#search_btn").click(function(){
		updateTable();
    });    
    jQuery('#excel_download_btn').click(function() {
        var params = {
            "member_id" : jQuery("#member_id").val(),
            "mobile_no" : jQuery("#mobile_no").val(),
            "progress_detail" : jQuery("#progress_detail").val(),
            "box_id" : jQuery("#box_id").val(),
            "shipping_no" : jQuery("#shipping_no").val(),
            "status" : jQuery("#status").val(),
            "processing_date_from" : jQuery("#processing_date_from").val(),
            "processing_date_to" : jQuery("#processing_date_to").val(),
            "start_date_from" : jQuery("#start_date_from").val(),
            "start_date_to" : jQuery("#start_date_to").val(),
            "end_date_from" : jQuery("#end_date_from").val(),
            "end_date_to" : jQuery("#end_date_to").val(),
            "request_status" : tab                 
        }
        run_waitMe($('.page-content'), 2, 'bounce');            
        $.ajax(  {url: "{{url('/api/getAcceptStatusListForExcel')}}", 
            type: 'POST',
            data: params , 
            success: function(result){  
                $('.page-content').waitMe('hide');             
                //console.log(result);
                if(result.status == 'success' && result.data.length > 0) {
                    if(tab == "PROGRESS") {
                        var createXLSLFormatObj = [];
                        var xlsHeader = ["No", "회원ID" , "핸드폰 번호" , "주소" , "진행상세" , "박스ID" , "운송번호" , "현황" , "처리일자"];    
                        var xlsRows = [];
                        for(let i = 0; i < result.data.length; i ++) {
                            xlsRows.push({
                                "No": result.data[i][0] ,
                                "회원ID": result.data[i][1] ,
                                "핸드폰 번호": result.data[i][2] ,
                                "주소": result.data[i][3] ,
                                "진행상세": result.data[i][4] ,
                                "박스ID": result.data[i][5] ,
                                "운송번호": result.data[i][6] ,
                                "현황": result.data[i][7] ,
                                "처리일자": result.data[i][8] 
                            });
                        }
                        createXLSLFormatObj.push(xlsHeader);
                        $.each(xlsRows, function(index, value) {
                            var innerRowData = [];
                            $.each(value, function(ind, val) {
                                innerRowData.push(val);
                            });
                            createXLSLFormatObj.push(innerRowData);
                        });
                        var filename = getCurrentDate() + "신청및배송현황_진행중.xlsx";
                        var ws_name = "진행중";
                        if (typeof console !== 'undefined') console.log(new Date());
                        var wb = XLSX.utils.book_new(),
                        ws = XLSX.utils.aoa_to_sheet(createXLSLFormatObj);
                        var wscols = [
                            {wch:10},
                            {wch:20},
                            {wch:20},
                            {wch:40},
                            {wch:15},
                            {wch:30},
                            {wch:20},
                            {wch:20},
                            {wch:20},
                        ];
                        ws['!cols'] = wscols;
                        XLSX.utils.book_append_sheet(wb, ws, ws_name);
                        if (typeof console !== 'undefined') console.log(new Date());
                        XLSX.writeFile(wb, filename);
                        if (typeof console !== 'undefined') console.log(new Date());        
                    }
                    else if(tab == "STORAGE") {
                        var createXLSLFormatObj = [];
                        var xlsHeader = ["No", "회원ID" , "핸드폰 번호" , "주소" , "박스" , "보관중인 물품수" , "보관 시작일" , "사용기간" , "자동연장" , "비고"];        
                        var xlsRows = [];
                        for(let i = 0; i < result.data.length; i ++) {
                            xlsRows.push({
                                "No": result.data[i][0] ,
                                "회원ID": result.data[i][1] ,
                                "핸드폰 번호": result.data[i][2] ,
                                "주소": result.data[i][3] ,
                                "박스": result.data[i][4] ,
                                "보관중인 물품수": result.data[i][5] ,
                                "보관 시작일": result.data[i][6] ,
                                "사용기간": result.data[i][7] ,
                                "자동연장": result.data[i][8] ,
                                "비고": result.data[i][9] 
                            });
                        }
                        createXLSLFormatObj.push(xlsHeader);
                        $.each(xlsRows, function(index, value) {
                            var innerRowData = [];
                            $.each(value, function(ind, val) {
                                innerRowData.push(val);
                            });
                            createXLSLFormatObj.push(innerRowData);
                        });
                        var filename = getCurrentDate() + "신청및배송현황_보관중.xlsx";
                        var ws_name = "보관중";
                        if (typeof console !== 'undefined') console.log(new Date());
                        var wb = XLSX.utils.book_new(),
                        ws = XLSX.utils.aoa_to_sheet(createXLSLFormatObj);
                        var wscols = [
                            {wch:10},
                            {wch:20},
                            {wch:20},
                            {wch:40},
                            {wch:15},
                            {wch:15},
                            {wch:15},
                            {wch:15},
                            {wch:20},
                            {wch:15}
                        ];
                        ws['!cols'] = wscols;
                        XLSX.utils.book_append_sheet(wb, ws, ws_name);
                        if (typeof console !== 'undefined') console.log(new Date());
                        XLSX.writeFile(wb, filename);
                        if (typeof console !== 'undefined') console.log(new Date());        
                    }
                    else if(tab == "FINISH") {
                        var createXLSLFormatObj = [];
                        var xlsHeader = ["No", "회원ID" , "핸드폰 번호" , "주소" , "박스ID" , "운송번호" , "현황" , "보관 시작일" , "보관 종료일" , "사용기간" , "자동연장" , "비고"];
                        var xlsRows = [];    
                        for(let i = 0; i < result.data.length; i ++) {
                            xlsRows.push({
                                "No": result.data[i][0] ,
                                "회원ID": result.data[i][1] ,
                                "핸드폰 번호": result.data[i][2] ,
                                "주소": result.data[i][3] ,
                                "박스ID": result.data[i][4] ,
                                "운송번호": result.data[i][5] ,
                                "현황": result.data[i][6] ,
                                "보관 시작일": result.data[i][7] ,
                                "보관 종료일": result.data[i][8] ,
                                "사용기간": result.data[i][9] ,
                                "자동연장": result.data[i][10] ,
                                "비고": result.data[i][11]
                            });
                        }
                        createXLSLFormatObj.push(xlsHeader);
                        $.each(xlsRows, function(index, value) {
                            var innerRowData = [];
                            $.each(value, function(ind, val) {
                                innerRowData.push(val);
                            });
                            createXLSLFormatObj.push(innerRowData);
                        });
                        var filename = getCurrentDate() + "신청및배송현황_종료.xlsx";
                        var ws_name = "종료";
                        if (typeof console !== 'undefined') console.log(new Date());
                        var wb = XLSX.utils.book_new(),
                        ws = XLSX.utils.aoa_to_sheet(createXLSLFormatObj);
                        var wscols = [
                            {wch:10},
                            {wch:20},
                            {wch:20},
                            {wch:40},
                            {wch:15},
                            {wch:15},
                            {wch:15},
                            {wch:15},
                            {wch:15},
                            {wch:20},
                            {wch:15}
                        ];
                        ws['!cols'] = wscols;
                        XLSX.utils.book_append_sheet(wb, ws, ws_name);
                        if (typeof console !== 'undefined') console.log(new Date());
                        XLSX.writeFile(wb, filename);
                        if (typeof console !== 'undefined') console.log(new Date());
                    }
                }
            }
        }); 
    })
});
function trans_click(transport_number) {
    window.open('http://www.ilogen.com/iLOGEN.Web.New/TRACE/TraceView.aspx?gubun=slipno&slipno=' + transport_number , null, 'height=660, width=730, status=no, resizable=no, scrollbars=yes, toolbar=no,location=no, menubar=no');
}
</script>
@yield('condition_script')
@endsection