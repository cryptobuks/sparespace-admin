@extends('layout')

@section('content')
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
			<form class="horizontal-form">
				<div class="form-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">제목</label>
								<input type="text" id="title" class="form-control">
							</div>
						</div>										
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">작성일</label>
								<div class="row">					
									<div class="col-md-6">
										<div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
											<input type="text" id="create_date_from" class="form-control form-filter input-sm" readonly name="order_date_from" placeholder="From">
											<span class="input-group-btn">
											<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
											</span>
										</div>
									</div>
									<div class="col-md-6">
										<div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
											<input type="text" id="create_date_to" class="form-control form-filter input-sm" readonly name="order_date_from" placeholder="From">
											<span class="input-group-btn">
											<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>	
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">내용</label>
								<input type="text" id="contents" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<button id="search_btn" type="button" class="btn btn-primary btn-sm button_style">검색</button>
							<button type="reset" class="btn btn-default btn-sm">초기화</button>
						</div>
					</div>			
				</div>
			</form>			
		</div>
	</div>
</div>	
<div>
	<a href="{{url('administrative/notice/view')}}" class="btn btn-primary btn-sm">+ 추가</a>
</div>
<br>
<table class="table table-bordered" id="main-content-table">
  <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">제목</th>
      <th scope="col">내용</th>
      <th scope="col">작성자</th>
      <th scope="col">작성일</th>      
    </tr>
  </thead>
  <tbody>
  	
  </tbody>
</table>
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
                "url": "{{url('api/getNoticeList')}}"
            },
            "order": [
                [1, "asc"]
            ]
        }
    });

    grid.getTableWrapper().on('click', 'tr', function (e) {
		/*var id = $(this).children("td:first").html();
		if(isNaN(id) == false){
			location.href="{{url('administrative/notice/view?id=')}}" + id;
		}*/		
    });

    function updateTable(){
    	grid.setAjaxParam("title", jQuery("#title").val());
        grid.setAjaxParam("contents", jQuery("#contents").val());
        grid.setAjaxParam("create_date_from", jQuery("#create_date_from").val());
        grid.setAjaxParam("create_date_to", jQuery("#create_date_to").val());
        grid.getDataTable().ajax.reload();
        grid.clearAjaxParams();
    }

    jQuery("#search_btn").click(function(){
		updateTable();
    });    
});
</script>
@endsection