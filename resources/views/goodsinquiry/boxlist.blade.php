@extends('layout')

@section('content')
<br>
<style>
  td {
    vertical-align: middle !important;
  }
</style>
<br>
<table class="table table-bordered" id="main-content-table">
  <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">박스이미지</th>
      <th scope="col">박스명</th>
      <th scope="col">규격</th>
      <th scope="col">사용중</th>
      <th scope="col">사용가능</th>
      <th scope="col">총 갯수</th>
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
                "url": "{{url('/api/getBoxList')}}"
            },
            "order": [
                [1, "asc"]
            ]
        }
    });
    grid.getTableWrapper().on('click', 'tr', function (e) {
  		/*var id = $(this).children("td:first").html();
  		if(isNaN(id) == false){
  			location.href="{{url('/goodsinquiry/boxview?id=')}}" + id;
  		}	*/	
    });
});
</script>
@endsection