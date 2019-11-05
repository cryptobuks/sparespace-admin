@extends('layout')

@section('content')
<div>
	<a href="{{url('administrative/faq/list')}}" id="search_btn" type="button" class="btn btn-primary btn-sm button_style">목록</a>
</div>
<br>
<div class="tab-pane" id="tab_1">
	<div class="portlet box blue">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-gift"></i>FAQ
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
			<form class="horizontal-form" id="faq_form" method="post" action="{{url('administrative/dofaq')}}">
				<div class="form-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">제목<span style="color: red;">*</span></label>
								<input type="text" name="title" class="form-control" value="@if($item != null){{$item->TITLE}} @endif" required>
							</div>
						</div>										
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">내용<span style="color: red;">*</span></label>
								<textarea name="contents" class="form-control" rows="6" required>@if($item != null){{$item->CONTENTS}} @endif</textarea>
							</div>
						</div>
					</div>
					<input type="hidden" name="id" value="@if($item != null){{$item->ID}} @endif"/>
					<div class="row">
						<div class="col-md-12">
							<button type="submit" class="btn btn-primary btn-sm button_style">저장</button>
							@if($item != null)
							<button type="reset" id="remove_button" class="btn btn-default btn-sm">삭제</button>
							@endif
						</div>
					</div>			
				</div>
			</form>			
		</div>
	</div>
</div>

<script src="{{url('js/jquery.validate.js')}}" type="text/javascript"></script>
<script type="text/javascript">
	$('#faq_form').validate({
		rules: {
			title: {
				required: true
			},
			contents: {
				required: true
			}
		},
		messages: {
			title: {
				required: "필수로 입력하셔야 합니다"
			},
			contents: {
				required: "필수로 입력하셔야 합니다"
			}
		}
	});

	$('#remove_button').on('click' , function(e) {
		if(!confirm("정말 삭제하시겠습니까?")) {
			return;
		}
		run_waitMe($('.page-content'), 2, 'bounce');            
		$.ajax({
			url: "{{url('/administrative/doRemove')}}", 
            type: 'POST',
            data: {
            	id: "@if($item != null){{$item->ID}} @endif" ,
            	type: "faq"
            } , 
            success: function(result){         
            	$('.page-content').waitMe('hide');      
                if(result.status == 'success') {
                	location.href = "/administrative/faq/list";
                }
           	}
     	}); 
	})

</script>
@endsection
