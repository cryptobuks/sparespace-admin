@extends('layout')

@section('content')
<div class="tab-pane" id="tab_1">
	<div class="portlet box blue">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-gift"></i>{{$form_title}}
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

@yield('main-content-tab')

@yield('main_table')

@endsection

@section('script')
<script type="text/javascript">
jQuery(document).ready(function(){

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
                "url": ""
            },
            "order": [
                [1, "asc"]
            ]
        }
    });

    function updateTable(){
    	grid.setAjaxParam("member_id", jQuery("#member_id").val());
        grid.setAjaxParam("mobile_no", jQuery("#mobile_no").val());
        grid.setAjaxParam("progress_detail", jQuery("#progress_detail").val());
        grid.setAjaxParam("box_id", jQuery("#box_id").val());
        grid.setAjaxParam("shipping_no", jQuery("#shipping_no").val());
        grid.setAjaxParam("status", jQuery("#status").val());
        grid.setAjaxParam("processing_date_from", jQuery("#processing_date_from").val());
        grid.setAjaxParam("processing_date_to", jQuery("#processing_date_to").val());
        grid.getDataTable().ajax.reload();
        grid.clearAjaxParams();
    }

    jQuery("#search_btn").click(function(){
		updateTable();
    });    
});
</script>
@yield('condition_script')
@endsection