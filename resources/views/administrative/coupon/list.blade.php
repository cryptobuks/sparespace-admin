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
								<label class="control-label">회원 ID</label>
								<input type="text" id="member_id" class="form-control">
							</div>
						</div>	
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">쿠폰</label>
								<input type="text" id="name" class="form-control">
							</div>
						</div>	
					</div>
					<div class="row">									
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">발급일</label>
								<div class="row">					
									<div class="col-md-6">
										<div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
											<input type="text" id="issue_date_from" class="form-control form-filter input-sm" readonly name="order_date_from" placeholder="From">
											<span class="input-group-btn">
											<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
											</span>
										</div>
									</div>
									<div class="col-md-6">
										<div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
											<input type="text" id="issue_date_to" class="form-control form-filter input-sm" readonly name="order_date_from" placeholder="From">
											<span class="input-group-btn">
											<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>	
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">사용일</label>
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
							<button id="search_btn" type="button" class="btn btn-primary btn-sm button_style">검색</button>
							<button type="reset" class="btn btn-default btn-sm">초기화</button>
						</div>
					</div>			
				</div>
			</form>			
		</div>
	</div>
</div>	

<br>
<table class="table table-bordered" id="main-content-table">
  <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">회원 ID</th>
      <th scope="col">쿠폰</th>
      <th scope="col">할인금액</th>
      <th scope="col">발급일</th>
      <th scope="col">사용일</th>      
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
            "pageLength": 20,
            "ajax": {
                "url": "{{url('api/getCouponList')}}"
            },
            "order": [
                [1, "asc"]
            ]
        }
    });

    function updateTable(){
    	grid.setAjaxParam("member_id" , jQuery("#member_id").val());
    	grid.setAjaxParam("name", jQuery("#name").val());
        grid.setAjaxParam("create_date_from", jQuery("#create_date_from").val());
        grid.setAjaxParam("create_date_to", jQuery("#create_date_to").val());
        grid.setAjaxParam("issue_date_from", jQuery("#issue_date_from").val());
        grid.setAjaxParam("issue_date_to", jQuery("#issue_date_to").val());
        grid.getDataTable().ajax.reload();
        grid.clearAjaxParams();
    }

    jQuery("#search_btn").click(function(){
		updateTable();
    });    
});
</script>
@endsection