@extends('layout')

@section('content')
<div class="tab-pane" id="tab_1">
	<div class="portlet box blue">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-gift"></i>관리자 정보
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
			<form class="horizontal-form" id="admin_form" method="post" action="{{url('/admin/doadmin')}}">
				<input type="hidden" name="ID" value="@if($item != null) {{$item->ID}} @endif"/>
				<div class="form-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">관리자명<span style="color: red;">*</span></label>
								<input type="text" id="ADMIN_NAME" name="ADMIN_NAME" class="form-control" @if($item != null) value="{{$item->ADMIN_NAME}}" @endif>
							</div>
						</div>									
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">Login ID<span style="color: red;">*</span></label>
								<input type="text" id="ADMIN_ID" name="ADMIN_ID" class="form-control" @if($item != null) value="{{$item->ADMIN_ID}}" @endif>
							</div>
						</div>	
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">Login Password<span style="color: red;">*</span></label>
								<input type="password" id="ADMIN_PWD" name="ADMIN_PWD" class="form-control" value="">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">사용여부<span style="color: red;">*</span></label>
								<div class="input-group">
									<div class="icheck-inline">
										<label>
										<input type="radio" id="USED" checked name="STATUS" class="icheck" value="0" @if($item != null) @if($item->STATUS == 0) checked @endif @endif>사용중</label>
										<label>
										<input type="radio" id="UNUSED" name="STATUS" class="icheck" value="1" @if($item != null) @if($item->STATUS == 1) checked @endif @endif>사용안함</label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">관리자 유형</label>
								<div class="input-group">
									<div class="icheck-inline">
										<label>
										<input type="radio" id="MASTER" checked name="TYPE" class="icheck" value="0" @if($item != null) @if($item->TYPE == 0) checked @endif @endif>Master</label>
										<label>
										<input type="radio" id="ADMIN" name="TYPE" class="icheck" value="1" @if($item != null) @if($item->TYPE == 0) checked @endif @endif>Admin</label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
							    <label for="exampleFormControlSelect1">권한</label>
							    <select class="form-control" name="PRIVILEGE_ID" id="PRIVILEGE_ID">
							      @foreach($permissions as $p)
							      	<option value="{{$p->ID}}" @if($permission->ID == $p->ID ) selected @endif>Privilege {{$p->ID}}</option>
							      @endforeach
							    </select>
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
  											<label><input disabled type="checkbox" class="icheck" name="STATUS" @if($permission->STATUS == "Y") checked @endif>1. 접수현황</label>
  										</td>
  										<td>
  											<label><input disabled type="checkbox" class="icheck" name="STATUS_1" @if($permission->STATUS_1 == "Y") checked @endif>1.1 상세메뉴 </label>
  										</td>
  									</tr>
  									<tr>
  										<td>
  											<label><input disabled type="checkbox" class="icheck" name="MEMBER" @if($permission->MEMBER == "Y") checked @endif>2. 회원관리</label>
  										</td>
  										<td>
  											<label><input disabled type="checkbox" class="icheck" name="MEMBER_1" @if($permission->MEMBER_1 == "Y") checked @endif>2.1 상세메뉴 </label>
  										</td>
  									</tr>
  									<tr>
  										<td>
  											
  										</td>
  										<td>
  											<label><input disabled type="checkbox" class="icheck" name="MEMBER_2" @if($permission->MEMBER_2 == "Y") checked @endif>2.2 상세메뉴 </label>
  										</td>
  									</tr>
  									<tr>
  										<td>
  											
  										</td>
  										<td>
  											<label><input disabled type="checkbox" class="icheck" name="MEMBER_3" @if($permission->MEMBER_3 == "Y") checked @endif>2.3 상세메뉴 </label>
  										</td>
  									</tr>
  									<tr>
  										<td>
  											<label><input disabled type="checkbox" class="icheck" name="GOODS" @if($permission->GOODS == "Y") checked @endif>3. 물품조회</label>
  										</td>
  										<td>
  											<label><input disabled type="checkbox" class="icheck" name="GOODS_1" @if($permission->GOODS_1 == "Y") checked @endif>3.1 상세메뉴 </label>
  										</td>
  									</tr>
  									<tr>
  										<td>
  											
  										</td>
  										<td>
  											<label><input disabled type="checkbox" class="icheck" name="GOODS_2" @if($permission->GOODS_2 == "Y") checked @endif>3.2 상세메뉴 </label>
  										</td>
  									</tr>
  									<tr>
  										<td>
  											<label><input disabled type="checkbox" class="icheck" name="MANAGE" @if($permission->MANAGE == "Y") checked @endif>4. 운영관리</label>
  										</td>
  										<td>
  											<label><input disabled type="checkbox" class="icheck" name="MANAGE_1" @if($permission->MANAGE_1 == "Y") checked @endif>4.1 상세메뉴 </label>
  										</td>
  									</tr>
  									<tr>
  										<td>
  										</td>
  										<td>
  											<label><input disabled type="checkbox" class="icheck" name="MANAGE_2" @if($permission->MANAGE_2 == "Y") checked @endif>4.2 상세메뉴 </label>
  										</td>
  									</tr>
                    <tr>
                      <td>
                      </td>
                      <td>
                        <label><input disabled type="checkbox" class="icheck" name="MANAGE_3" @if($permission->MANAGE_3 == "Y") checked @endif>4.3 상세메뉴 </label>
                      </td>
                    </tr>
                    <tr>
                      <td>
                      </td>
                      <td>
                        <label><input disabled type="checkbox" class="icheck" name="MANAGE_4" @if($permission->MANAGE_4 == "Y") checked @endif>4.4 상세메뉴 </label>
                      </td>
                    </tr>
                    <tr>
                      <td>
                      </td>
                      <td>
                        <label><input disabled type="checkbox" class="icheck" name="MANAGE_5" @if($permission->MANAGE_5 == "Y") checked @endif>4.5 상세메뉴 </label>
                      </td>
                    </tr>
  									<tr>
  										<td>
  											<label><input disabled type="checkbox" class="icheck" name="ADMIN" @if($permission->ADMIN == "Y") checked @endif>5. 관리자관리</label>
  										</td>
  										<td>
  											<label><input disabled type="checkbox" class="icheck" name="ADMIN_1" @if($permission->ADMIN_1 == "Y") checked @endif>5.1 상세메뉴 </label>
  										</td>
  									</tr>
  									<tr>
  										<td>
  										</td>
  										<td>
  											<label><input disabled type="checkbox" class="icheck" name="ADMIN_2" @if($permission->ADMIN_2 == "Y") checked @endif>5.2 상세메뉴 </label>
  										</td>
  									</tr>
  								</tbody>
  							</table>
						</div>
					</div>
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
	$('#PRIVILEGE_ID').on('change', function(event){
		@if($item != null)
	  		location.href="{{url('admin/user/view?id='.$item->ID)}}" + "&p_id=" + $(this).val();
	  	@else
	  		location.href="{{url('admin/user/view')}}" + "?p_id=" + $(this).val();
		@endif
	});
  $('#reset_button').click(function(e) {
      $('#ADMIN_NAME').val('');
      $('#ADMIN_ID').val('');
      $('#ADMIN_PWD').val('');
      $('#USED').iCheck('check');
      $('#UNUSED').iCheck('uncheck');
      $('#MASTER').iCheck('check');
      $('#ADMIN').iCheck('uncheck');
  });
});

$('#admin_form').validate({
    rules: {
      ADMIN_NAME: {
        required: true
      } , 
      ADMIN_ID: {
        required: true
      } ,
      ADMIN_PWD: {
        required: true
      }
    },
    messages: {
      ADMIN_NAME: {
        required: "필수로 입력하셔야 합니다" 
      } , 
      ADMIN_ID: {
        required: "필수로 입력하셔야 합니다" 
      } , 
      ADMIN_PWD: {
        required: "필수로 입력하셔야 합니다" 
      } 
    }
  });
</script>
@endsection