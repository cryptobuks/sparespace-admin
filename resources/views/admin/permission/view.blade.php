@extends('layout')

@section('content')
<div class="tab-pane" id="tab_1">
	<div class="portlet box blue">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-gift"></i> 권한설정
			</div>
			<div class="tools">
				<a href="javascript:;" class="collapse">
				</a>
				<a href="#portlet-config" data-toggle="modal" class="config">
				</a>
				<a href="javascript:;" class="reload">
				</a>
				<a href="javascript:;" class="remove">
				</a>
			</div>
		</div>
		<div class="portlet-body form">
			<form class="horizontal-form" method="post" id="permission_form" action="{{url('admin/dopermission')}}">
				<div class="form-body">					
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">관리자 유형<span style="color: red;">*</span></label>
								<div class="input-group">
									<div class="icheck-inline">
										<label>
										<input type="radio" id="RADIO_MASTER" name="ADMIN_TYPE" class="icheck" value="1" @if($item != null) @if($item->ADMIN_TYPE == 1) checked @endif @else checked @endif>Master</label>
										<label>
										<input type="radio" id="RADIO_ADMIN" name="ADMIN_TYPE" class="icheck" value="2" @if($item != null) @if($item->ADMIN_TYPE == 2) checked @endif @endif>Admin</label>										
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">권한명<span style="color: red;">*</span></label>
								<input type="text" id="NAME" name="NAME" @if($item != null) value="{{$item->NAME}}" @endif class="form-control">
							</div>
						</div>	
					</div>
					<div class="row">								
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">설명</label>
								<input type="text" id="DESC" name="DESC" @if($item != null) value="{{$item->DESC}}" @endif class="form-control">
							</div>
						</div>	
					</div>
						
					<div class="row">
						<div class="col-md-12">
							<table class="table table-bordered">
  								<thead>
  									<tr>
  										<th>1Depth</th>
  										<th>2Depth</th>
  									</tr>
  								</thead>
  								<tbody>
  									<tr>
  										<td>
  											<label><input type="checkbox" class="icheck" id="STATUS" name="STATUS" @if($item != null) @if($item->STATUS == "Y") checked @endif @endif>1. 접수현황</label>
  										</td>
  										<td>
  											<label><input type="checkbox" class="icheck" id="STATUS_1" name="STATUS_1" @if($item != null) @if($item->STATUS_1 == "Y") checked @endif @endif>1.1 상세메뉴 </label>
  										</td>
  									</tr>
  									<tr>
  										<td>
  											<label><input type="checkbox" class="icheck" id="MEMBER" name="MEMBER" @if($item != null) @if($item->MEMBER == "Y") checked @endif @endif>2. 회원관리</label>
  										</td>
  										<td>
  											<label><input type="checkbox" class="icheck" id="MEMBER_1" name="MEMBER_1" @if($item != null) @if($item->MEMBER_1 == "Y") checked @endif @endif>2.1 상세메뉴 </label>
  										</td>
  									</tr>
  									<tr>
  										<td>
  											
  										</td>
  										<td>
  											<label><input type="checkbox" class="icheck" id="MEMBER_2" name="MEMBER_2" @if($item != null) @if($item->MEMBER_2 == "Y") checked @endif @endif>2.2 상세메뉴 </label>
  										</td>
  									</tr>
  									<tr>
  										<td>
  											
  										</td>
  										<td>
  											<label><input type="checkbox" class="icheck" id="MEMBER_3" name="MEMBER_3" @if($item != null) @if($item->MEMBER_3 == "Y") checked @endif @endif>2.3 상세메뉴 </label>
  										</td>
  									</tr>
  									<tr>
  										<td>
  											<label><input type="checkbox" class="icheck" id="GOODS" name="GOODS" @if($item != null) @if($item->GOODS == "Y") checked @endif @endif>3. 물품조회</label>
  										</td>
  										<td>
  											<label><input type="checkbox" class="icheck" id="GOODS_1" name="GOODS_1" @if($item != null) @if($item->GOODS_1 == "Y") checked @endif @endif>3.1 상세메뉴 </label>
  										</td>
  									</tr>
  									<tr>
  										<td>
  										</td>
  										<td>
  											<label><input type="checkbox" class="icheck" id="GOODS_2" name="GOODS_2" @if($item != null) @if($item->GOODS_2 == "Y") checked @endif @endif>3.2 상세메뉴 </label>
  										</td>
  									</tr>
  									<tr>
  										<td>
  											<label><input type="checkbox" class="icheck" id="MANAGE" name="MANAGE" @if($item != null) @if($item->MANAGE == "Y") checked @endif @endif>4. 운영관리</label>
  										</td>
  										<td>
  											<label><input type="checkbox" class="icheck" id="MANAGE_1" name="MANAGE_1" @if($item != null) @if($item->MANAGE_1 == "Y") checked @endif @endif>4.1 상세메뉴 </label>
  										</td>
  									</tr>
  									<tr>
  										<td>
  										</td>
  										<td>
  											<label><input type="checkbox" class="icheck" id="MANAGE_2" name="MANAGE_2" @if($item != null) @if($item->MANAGE_2 == "Y") checked @endif @endif>4.2 상세메뉴 </label>
  										</td>
  									</tr>
                    <tr>
                      <td>
                      </td>
                      <td>
                        <label><input type="checkbox" class="icheck" id="MANAGE_3" name="MANAGE_3" @if($item != null) @if($item->MANAGE_3 == "Y") checked @endif @endif>4.3 상세메뉴 </label>
                      </td>
                    </tr>
                    <tr>
                      <td>
                      </td>
                      <td>
                        <label><input type="checkbox" class="icheck" id="MANAGE_4" name="MANAGE_4" @if($item != null) @if($item->MANAGE_4 == "Y") checked @endif @endif>4.4 상세메뉴 </label>
                      </td>
                    </tr>
                    <tr>
                      <td>
                      </td>
                      <td>
                        <label><input type="checkbox" class="icheck" id="MANAGE_5" name="MANAGE_5" @if($item != null) @if($item->MANAGE_5 == "Y") checked @endif @endif>4.5 상세메뉴 </label>
                      </td>
                    </tr>
  									<tr>
  										<td>
  											<label><input type="checkbox" class="icheck" id="ADMIN" name="ADMIN" @if($item != null) @if($item->ADMIN == "Y") checked @endif @endif>5. 관리자관리</label>
  										</td>
  										<td>
  											<label><input type="checkbox" class="icheck" id="ADMIN_1" name="ADMIN_1" @if($item != null) @if($item->ADMIN_1 == "Y") checked @endif @endif>5.1 상세메뉴 </label>
  										</td>
  									</tr>
  									<tr>
  										<td>
  										</td>
  										<td>
  											<label><input type="checkbox" class="icheck" id="ADMIN_2" name="ADMIN_2" @if($item != null) @if($item->ADMIN_2 == "Y") checked @endif @endif>5.2 상세메뉴 </label>
  										</td>
  									</tr>
  								</tbody>
  							</table>
						</div>
					</div>
					<input type="hidden" name="ID" value="@if($item != null) {{$item->ID}} @endif"/>
					<div class="row">
						<div class="col-md-12">
							<button id="search_btn" type="submit" class="btn btn-primary btn-sm button_style">저장</button>
							<button type="button" id="reset_button" class="btn btn-default btn-sm">초기화</button>
						</div>
					</div>			
				</div>
			</form>			
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="{{url('js/jquery.validate.js')}}" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
	$('input').on('ifChecked', function(event){
	  	$("#admin_type").val($(this).val());
	});

  $('#reset_button').click(function(e) {
      $('#NAME').val('');
      $('#DESC').val('');
      ////////////////////////
      $('#RADIO_MASTER').iCheck('check');
      $('#RADIO_ADMIN').iCheck('uncheck');
      ////////////////////////
      $('#STATUS').iCheck('uncheck');
      $('#STATUS_1').iCheck('uncheck');
      $('#MEMBER').iCheck('uncheck');
      $('#MEMBER_1').iCheck('uncheck');
      $('#MEMBER_2').iCheck('uncheck');
      $('#MEMBER_3').iCheck('uncheck');
      $('#GOODS').iCheck('uncheck');
      $('#GOODS_1').iCheck('uncheck');
      $('#GOODS_2').iCheck('uncheck');
      $('#MANAGE').iCheck('uncheck');
      $('#MANAGE_1').iCheck('uncheck');
      $('#MANAGE_2').iCheck('uncheck');
      $('#MANAGE_3').iCheck('uncheck');
      $('#MANAGE_4').iCheck('uncheck');
      $('#MANAGE_5').iCheck('uncheck');
      $('#ADMIN').iCheck('uncheck');
      $('#ADMIN_1').iCheck('uncheck');
      $('#ADMIN_2').iCheck('uncheck');
  })

});

$('#permission_form').validate({
    rules: {
      NAME: {
        required: true
      }
    },
    messages: {
      NAME: {
        required: "필수로 입력하셔야 합니다" 
      }
    }
  });

</script>
@endsection