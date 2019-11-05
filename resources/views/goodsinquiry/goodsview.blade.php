@extends('layout')

@section('content')

<?php
	$CONDITION_LABELS = [
		"Custom_Delivery" => "고객 전달중" ,
		"RequestCancel_RA" => "신청취소" ,
		"RequestCancel_RH" => "신청취소(위약금)" ,
		"Returning_Standby" => "회송대기" ,
		"Returning" => "회송중" ,
		"Returning_Impropriety" => "회송불가" ,
		"Goods_Confirm" => "물품 확인중" , 
		"Finish" => "완료"
	];
?>



<form action="{{url('/goodsinquiry/doprocess')}}" method="post" id="goods_form">
	<h3>{{$item->BOXID}}({{$status_array[$item->MANAGE_STATUS]}})</h3>
	<div class="flex-between">
		<h5>보관종료일: {{$item->END_DATE}}</h5>
		@if($item->MANAGE_STATUS != 2 && $item->MANAGE_STATUS != 3)
		<div class="fileinput fileinput-new" data-provides="fileinput" id="new-image">
			<span class="btn default btn-file">
				<span class="fileinput-new">이미지 업로드</span>
				<input id="uploadImage" type="file" name="image" multiple>
				<span class="fileinput-exists">이미지 업로드 </span>
			</span>
		</div>
		@endif
		@if($item->MANAGE_STATUS == 3)
		<h15>운송번호: {{$item->TRANS_NUMBER}} {{($item->CONDITION == '')?'':('('.$CONDITION_LABELS[$item->CONDITION].')')}}</h5>
		@endif
	</div>
	<div class="flex-center" id="goods_container">
		<div id="preview">
			<?php
			$k = 0;
			if($item->MANAGE_STATUS == 4) {
				?>
				<?php 
				$k = 0;
				?>
				@foreach($item->goods as $good_item)
					<div class="fileinput fileinput-new space-margin imgbox" data-id="{{ $k }}" data-provides="fileinput" id="fileinput_{{ $k }}">

						<?php
						if($good_item->RESET_YN == 'N')  {
							?>
							<div class="fileinput-new thumbnail" style="width: 100%; height: 150px; margin-bottom: 15px;">
							<img src="{{ $good_item->IMAGE }}" style="height:140px;">
						</div>
						<div class="fileinput-preview fileinput-exists thumbnail" style="width: 100%; height: 150px;">						
						</div>
						<?php
					}
					else {
						?>
						<div class="fileinput-new thumbnail" style="width: 100%; height: 150px; margin-bottom: 15px; display: flex; position: relative; justify-content: center; align-items: center;">
							<img src="{{ $good_item->IMAGE }}" style="height:140px;">
							<div style="position: absolute; font-size: 36px; color: red;">
								찾기완료
							</div>
						</div>
						<div class="fileinput-preview fileinput-exists thumbnail" style="width: 100%; height: 150px;">						
						</div>
						<?php
					}
						?>
						<?php
						if($good_item->RESET_YN == 'N')  {
							?>
							<div>
								<div class="form-group form-inline">
									<label class="control-label clear-margin-bottom">물품명:</label>
									<input type="text" data-type="name" id="imgbox_name_{{ $k }}" class="form-control name_list loop" value="{{ $good_item->GOODS_NAME }}">
								</div>
							</div>
							<?php
						}
						else {
							?>
							<div>
								<div class="form-group form-inline" disabled>
									<label class="control-label clear-margin-bottom" disabled>물품명:</label>
									<input type="text" data-type="name" id="imgbox_name_{{ $k }}" class="form-control name_list loop" disabled="" value="{{ $good_item->GOODS_NAME }}">
								</div>
							</div>
							<?php
						}
						?>
						<div>
							<div class="form-group form-inline">
								<label class="control-label clear-margin-bottom">물품ID:</label>
								<label class="control-label clear-margin-bottom" id="goods_id_{{$k}}" style="margin-left: 5px;">{{ $good_item->GOODSID }}</label>
							</div>
						</div>
						<?php
						if($good_item->RESET_YN == 'N')  {
							?>
							<div class="flex-center">
								<span class="btn default btn-file">
									<span class="">이미지 변경 </span>
									<input type="button" id="imgbox_file_{{ $k }}" name="image" data-id="{{ $k }}" class="imgbox-file loop">
								</span>
								<a data-id="{{ $k }}" class="btn red imgbox-del"  goods_id="{{ $good_item->GOODSID }}" >삭제 </a>
							</div>	
							<?php
						}
						else {
							?>
							<div class="flex-center">
								<span class="btn default btn-file" disabled>
									<span class="">이미지 변경 </span>
									<input id="imgbox_file_{{ $k }}" name="image" data-id="{{ $k }}" class="imgbox-file loop" readonly="" disabled="">
								</span>
								<a data-id="{{ $k }}" class="btn red imgbox-del"  goods_id="{{ $good_item->GOODSID }}" disabled>삭제 </a>
							</div>	
							<?php
						}
						?>

						<input type="hidden" class="goods_id_class" value="{{ $good_item->GOODSID }}" />
					</div>
					<?php
					$k ++;
					?>
				@endforeach	
				<?php
			}
			?>
		</div>
	</div>
	<div class="flex-center">
		@if($item->MANAGE_STATUS != 2 && $item->MANAGE_STATUS != 3)
		<span id="uploaded_image">이미지를 업로드해주세요.</span>
		@else
		<p>보관이 불가한 상태입니다.</p>
		@endif
	</div>
	<input type="hidden" name="action" id="action" value=""/>
	<input type="hidden" name="box_id" value="{{$item->BOXID}}"/>
	<div class="flex-center" style="margin-top: 15px;">
		@if($item->MANAGE_STATUS == 1)
		<button id="btn_save" type="button" class="btn btn-primary btn-sm">보관하기</button>
		<button id="btn_impossibility" type="button" class="btn btn-primary btn-sm">검수불가</button>
		@endif
		@if($item->MANAGE_STATUS == 2)
		<button id="btn_check" type="button" class="btn btn-primary btn-sm">검수하기</button>
		<button id="btn_return" type="button" class="btn btn-primary btn-sm">반송처리</button>
		@endif
		@if($item->MANAGE_STATUS == 3)
		<button id="btn_confirm" type="button" class="btn btn-primary btn-sm">확인</button>
		@endif
		@if($item->MANAGE_STATUS == 4)
		<button id="btn_save" type="button" class="btn btn-primary btn-sm">보관하기</button>
		@endif
	</div>
</form>
@endsection

@section('script')
<script type="text/javascript">
var total_files = new Array();
var boxid = '{{$item->BOXID}}';
function format_data(data) {
	if(data < 10)
		return '0' + data;
	else
		return data;
}

jQuery(document).ready(function(){
	// local file array
	var glob_idx = 0 , real_count = 0;

	var images = [] , server = [];
	var k ={{$k}};

	glob_idx = k;
	real_count = k;

	var remove_goods_ids = [];
	for(let index = 0; index < k; index ++) {
		total_files[index] = "";
		images[index] = 0;
	//	server[index] = true;
	}
	index = 0;
	@foreach($item->goods as $good_item)
		server[index ++] = '{{$good_item->GOODSID}}';
	@endforeach	

	//alert(server);

	//검수불가하기 단추 누른경우
	$("#btn_impossibility").click(function(){
		if(confirm("검수불가로 변경하시겠습니까?")) {
			$("#action").val("impossibility");
			$("#goods_form").submit();
		}
	});
	// 반송처리
	$("#btn_return").click(function(){

		if( !confirm(Message.return_msg) )
			return;

		$("#action").val("return");
		$("#goods_form").submit();
	});
	//검수하기
	$("#btn_check").click(function(){
		$("#action").val("check");
		$("#goods_form").submit();
	});
	$("#btn_confirm").click(function() {
		location.href = "/admin/goodsinquiry/manage";
	})
	$("#new-image").on('change.bs.fileinput', function(e){
		var i;
		var t_glob = glob_idx;
		for(i = 0; i < document.getElementById("uploadImage").files.length; i++) {
			// convert file to base64
			var reader = new FileReader();
			var base64File = "";
			reader.onload = function(readerEvt) {
	            var binaryString = readerEvt.target.result;
	            base64File = btoa(binaryString);
	            // add imgbox
	        var imgbox_html = '<div class="fileinput fileinput-new space-margin imgbox" data-id="'+real_count+'" data-provides="fileinput" id="fileinput_'+real_count+'">';
				imgbox_html += '<div class="fileinput-new thumbnail" style="width: 100%; height: 150px; margin-bottom: 15px;">';
				imgbox_html += 		'<img src="data:image/png;base64,'+base64File+'" style="height:140px;">';
				imgbox_html += '</div>';
				imgbox_html += '<div class="fileinput-preview fileinput-exists thumbnail" style="width: 100%; height: 150px;">';
				imgbox_html += '</div>';
				imgbox_html += '<div>';
				imgbox_html += 		'<div class="form-group form-inline">';
				imgbox_html += 			'<label class="control-label clear-margin-bottom">물품명:</label>';
				imgbox_html += 			'<input type="text" data-type="name" id="imgbox_name_'+real_count+'" value="물품' + (real_count + 1) + '" class="form-control name_list loop">';
				imgbox_html += 		'</div>';
				imgbox_html += '</div>';

				imgbox_html += '<div>';
				imgbox_html += 		'<div class="form-group form-inline">';
				imgbox_html += 			'<label class="control-label clear-margin-bottom">물품ID:</label>';
				imgbox_html += 			'<label class="control-label clear-margin-bottom" style="margin-left: 5px;" id="goods_id_' + real_count + '">' + boxid + 'T' + format_data(glob_idx + 1) + '</label>';
				imgbox_html += 		'</div>';
				imgbox_html += '</div>';


				imgbox_html += '<div class="flex-center">';
				imgbox_html += 		'<span class="btn default btn-file">';
				imgbox_html += 			'<span class="">이미지 변경 </span>';
				imgbox_html += 			'<input type="file" id="imgbox_file_'+real_count+'" name="image" data-id="'+real_count+'" class="imgbox-file loop">';
				imgbox_html += 		'</span>';
				imgbox_html += 		'<a data-id="'+real_count+'" class="btn red imgbox-del" goods_id="">삭제 </a>';
				imgbox_html += '</div>';
			   imgbox_html += '</div>';
				$("#preview").append(imgbox_html);
				glob_idx++;
				real_count ++;
	        };
	        reader.readAsBinaryString(document.getElementById("uploadImage").files[i]);
	        total_files[t_glob+i] = document.getElementById("uploadImage").files[i];
	        images[t_glob + i] = true;
	        server[t_glob + i] = 0;

		}
		$('#uploaded_image').html("");
	});


	$('.imgbox-file.loop').on('change' , function(e) {
		var id = $(this).data('id');
		var kk = 0 , flag = 0;
		$('.imgbox-file.loop').each(function(i) {
			if($(this).attr('data-id') == id)
			{
				flag = 1;
			}	
			else {
				if(flag == 0)
					kk ++;	
			}
		});
		total_files[kk] = document.getElementById("imgbox_file_"+id).files[0];
		images[kk] = 1;
	});
	/*$(document).on('change', '#imgbox_file_0', function(e){
		alert(id);
		var id = $(this).data('id');
		var kk = 0 , flag = 0;
		$('.imgbox-file.loop').each(function(i) {
			if($(this).attr('data-id') == id)
			{
				flag = 1;
			}	
			else {
				if(flag == 0)
					kk ++;	
			}
		});
		total_files[kk] = document.getElementById("imgbox_file_"+id).files[0];
		images[kk] = 1;
	});*/

	$(document).on('click', '.imgbox-del', function(){

		var goods_id = $(this).attr('goods_id');
		var data_id = $(this).attr('data-id');
		if(goods_id != '')
			remove_goods_ids.push(goods_id);

		//goods_id_

		var kk = 0 , flag = 0 , goods_counter = 0;
		$('.btn.red.imgbox-del').each(function(i) {
			if($(this).attr('data-id') == data_id)
			{
				flag = 1;
			}	
			else {
				if(flag == 0) {
					kk ++;	
					goods_counter ++;
				}
				else {
					goods_counter ++;
					$('#goods_id_' + $(this).attr('data-id')).html(boxid + 'T' + format_data(goods_counter));
				}
			}
		});

		var id = $(this).data('id');
		$("#fileinput_"+id).remove();


		//total_files[parseInt(id)] = "";
		total_files.splice(kk , 1);
		images.splice(kk , 1);
		server.splice(kk , 1);

		glob_idx --;

		var imgbox_no = 0;
		$(".imgbox").each(function(){
			imgbox_no++;
		});
		if(imgbox_no == 0) {
			$('#uploaded_image').html("이미지를 업로드해주세요.");
		}
	});


	$("#btn_save").click(function(e) {
		e.preventDefault();
		var form_data = new FormData();
		var i;
		var names = "" , images_str = "" , server_str = "";
		var j = 0;

		console.log(total_files);

		for(i = 0; i < glob_idx; i++)
		{
			if(total_files[i] != "") {

				var name = total_files[i].name;
				var ext = name.split('.').pop().toLowerCase();
				if(jQuery.inArray(ext, ['gif','png','jpg','jpeg']) == -1)
				{
					alert("Invalid Image File");
					return;
				}
				if($("#imgbox_name_"+i).val() == "")
				{
					alert("fill in the names");
					return;
				}
				form_data.append("files[]", total_files[i]);
				/*if(j == 0) {
					names += $("#imgbox_name_"+i).val();
				}
				else {
					names += ";;;;" + $("#imgbox_name_"+i).val();
				}*/
				j++;
			}
		}

		j = 0;
		names = ''; 
		images_str = '';
		$('.name_list.loop').each(function(i) {
			if(j == 0)
				names += $(this).val();
			else 
				names += ";;;;" + $(this).val();
			j ++;
		});

		images_str = images.join(";;;;");
		server_str = server.join(";;;;");


		form_data.append("names", names);
		form_data.append("images", images_str);
		form_data.append("server", server_str);

		form_data.append("remove_goods_ids" , remove_goods_ids.join(";;;;"));

		form_data.append("id", "{{$item->BOXID}}");
		form_data.append("_token", "{{csrf_token()}}");
		if(j > 0) {
			if(confirm("보관중으로 이동후에는 변경이 안됩니다. 그래도 진행하시겠습니까?")) {
				run_waitMe($('.page-content'), 2, 'bounce');            
				$.ajax(  {url: "{{url('/goodsinquiry/sendmessage')}}", 
				type: 'POST',
				data: {
					"box_id": boxid , 
					"action" : "check"
				} , 
				success: function(result){
					
					$.ajax({
						url: "{{url('goodsinquiry/uploadtempimg')}}",
						type: "POST",
						data: form_data,
						dataType: 'json',
						contentType: false,
						processData: false,
						beforeSend:function(){
						},
						success:function(data) {
							$('.page-content').waitMe('hide');      
							location.href = "{{ url('goodsinquiry/manage') }}";
						}
					}); 
				}});
	 		}
		}
	});
});
</script>
@endsection