@extends('member.layout')


@section('search_form')
<form class="horizontal-form" method="post" action="{{url('/member/doinquiry')}}">
	<div class="form-body">		
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">회원 ID</label>
					<input type="text" id="member_id" class="form-control" readonly value="{{$item->user->USERID}}">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Email</label>
					<input type="text" id="email" class="form-control" readonly value="{{$item->user->USER_EMAIL}}">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">문의시간</label>
					<input type="text" id="member_id" class="form-control" readonly value="{{date("Y-m-d h:i:sa",$item->user->REGTIME)}}">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label">내용</label>
					<textarea type="text" id="member_id" class="form-control" readonly>{{$item->QUERY}}</textarea>
				</div>
			</div>			
		</div>
		@if(trim($item->ANSWER) != "")
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label">답변시간</label>
					<input type="text" id="member_id" class="form-control" readonly value="{{date("Y-m-d h:i:sa",$item->AWRTIME)}}">
				</div>
			</div>
		</div>
		@endif
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label">답변내용</label>
					<textarea name="answer" type="text" id="member_id" rows="5" class="form-control">@if(trim($item->ANSWER) != ""){{$item->ANSWER}}@endif</textarea>
					<input type="hidden" value="{{$item->ID}}" name="id"/>
				</div>
			</div>			
		</div>
		<div class="row">
			<div class="col-md-12">
				<button type="submit" class="btn btn-primary btn-sm">@if(trim($item->ANSWER) != "") 답변수정 @else 답변등록 @endif</button>
			</div>
		</div>
	</div>
</form>
@endsection


@section('script')
<script type="text/javascript">
jQuery(document).ready(function(){
	
	$(".icheck").change(function(){
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
                [10, 20, -1],
                [10, 20, "All"]
            ],
            "pageLength": 10,
            "ajax": {
                "url": "{{url('/api/getInquiryList')}}"
            },
            "order": [
                [1, "asc"]
            ]
        }
    });

    function updateTable(){
    	grid.setAjaxParam("member_id", jQuery("#member_id").val());
        grid.setAjaxParam("email", jQuery("#email").val());
        grid.setAjaxParam("note", jQuery("#note").val());
        grid.setAjaxParam("status", $("#inquiry_status").val());
        grid.setAjaxParam("processing_date_from", jQuery("#processing_date_from").val());
        grid.setAjaxParam("processing_date_to", jQuery("#processing_date_to").val());
        grid.setAjaxParam("inquiry_time_to", jQuery("#inquiry_time_to").val());
        grid.setAjaxParam("inquiry_time_from", jQuery("#inquiry_time_from").val());
        grid.getDataTable().ajax.reload();
        grid.clearAjaxParams();
    }

    jQuery("#search_btn").click(function(){
		updateTable();
    });    
});
</script>

@endsection