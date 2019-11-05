@extends('layout')

@section('content')
<div class="tab-pane" id="tab_1">
	<div class="portlet box blue">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-gift"></i>박스정보
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
			<form class="horizontal-form" method="post" action="{{url('goodsinquiry/dobox')}}" enctype="multipart/form-data">
				<div class="form-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">박스명</label>
								<input type="text" name="name" class="form-control" value="@if($item != null){{$item->NAME}}@endif">
							</div>
						</div>
						<div class="col-md-1">
							<div class="form-group">
								<label class="control-label">규격</label>
								<input type="text" name="width" class="form-control col-md-1" value="@if($item != null){{$item->WIDTH}}@endif">																
							</div>
						</div>
						<div class="col-md-1">
							<div class="form-group">
								<label class="control-label">&nbsp;</label>
								<label class="form-control border-none">cm &nbsp;X </label>
							</div>
						</div>
						<div class="col-md-1">
							<div class="form-group">
								<label class="control-label">&nbsp;</label>
								<input type="text" name="length" class="form-control col-md-1" value="@if($item != null){{$item->LENGTH}}@endif">
							</div>
						</div>
						<div class="col-md-1">
							<div class="form-group">
								<label class="control-label">&nbsp;</label>
								<label class="form-control border-none">cm &nbsp;X </label>
							</div>
						</div>
						<div class="col-md-1">
							<div class="form-group">
								<label class="control-label">&nbsp;</label>
								<input type="text" name="height" class="form-control col-md-1" value="@if($item != null){{$item->HEIGHT}}@endif">
							</div>
						</div>
						<div class="col-md-1">
							<div class="form-group">
								<label class="control-label">&nbsp;</label>
								<label class="form-control border-none">cm</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">사용중</label>
								<input type="text" name="using_count" class="form-control" value="@if($item != null){{$item->USING_COUNT}}@endif">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">사용가능</label>
								<input type="text" name="available_count" class="form-control" value="@if($item != null){{$item->AVAILABLE_COUNT}}@endif">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">총 갯수</label>
								<input type="text" name="total_count" class="form-control" value="@if($item != null){{$item->TOTAL_COUNT}}@endif">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">최대 무게</label>
								<input type="text" name="max_weight" class="form-control" value="@if($item != null){{$item->MAX_WEIGHT}}@endif">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<label class="control-label">보관 예상양</label>
							<textarea class="form-control" rows="4" name="expect_qty">@if($item != null){{$item->EXPECT_QTY}}@endif</textarea>
						</div>
					</div>
					<div class="row" style="margin-top: 20px;">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">대표이미지1</label>
								<div>
									<div class="fileinput fileinput-new" data-provides="fileinput">
										<span class="btn default btn-file">				
											<span class="fileinput-new">
												Select file 
											</span>
											<span class="fileinput-exists">
												Change 
											</span>
											<input type="file" name="image1">
										</span>
										<span class="fileinput-filename">
											{{ ($item==null || $item->FILE_NAME1==null)?'':$item->FILE_NAME1 }}
										</span>
										&nbsp; <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput">
										</a>
										<div class="fileinput-preview">
											@if($item != null && $item->IMAGE1 != '')
											<img src="{{ $item->IMAGE1 }}" style="width: 200px; height: 200px;" />
											@endif
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">대표이미지2</label>
								<div>
									<div class="fileinput fileinput-new" data-provides="fileinput">
										<span class="btn default btn-file">
											<span class="fileinput-new">
												Select file 
											</span>
											<span class="fileinput-exists">
												Change 
											</span>
											<input type="file" name="image2">
										</span>
										<span class="fileinput-filename">
											{{ ($item==null || $item->FILE_NAME2==null)?'':$item->FILE_NAME2 }}
										</span>
										&nbsp; <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput">
										</a>
										<div class="fileinput-preview">
											@if($item != null && $item->IMAGE2 != '')
											<img src="{{ $item->IMAGE2 }}" style="width: 200px; height: 200px;" />
											@endif
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<input type="hidden" name="id" value="@if($item != null){{$item->ID}}@endif"/>
					<div class="row" style="margin-top: 20px;">
						<div class="col-md-12">
							<button id="search_btn" type="submit" class="btn btn-primary btn-sm button_style">저장</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection