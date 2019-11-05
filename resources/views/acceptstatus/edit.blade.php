@extends('layout')

@section('content')
<h2>변경</h2>

<form method="POST" action="{{ url('acceptstatus/edit') }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="ID" value="{{$reques->ID}}" >

    <div class="form-group">
        <label>진행상세</label>
        <select class="form-control" name="SDETAIL" id="SDETAIL">
            <option value="SHIPPING" {{ $reques->SDETAIL=='SHIPPING'?'selected':'' }}>배송중</option>
            <option value="ARRANGE" {{ $reques->SDETAIL=='ARRANGE'?'selected':'' }}>물품정리중</option>
            <option value="COLLECT" {{ $reques->SDETAIL=='COLLECT'?'selected':'' }}>회수중</option>
            <option value="CHECK" {{ $reques->SDETAIL=='CHECK'?'selected':'' }}>검수중</option>
        </select>
    </div>

    <div class="form-group">
        <label>운송번호</label>
        <input class="form-control" value="{{$reques->TRANS_NUMBER}}" name="TRANS_NUMBER">
    </div>

    <div class="form-group">
        <label>현황</label>
        <input class="form-control" value="{{$reques->CONDITION}}" name="CONDITION" id="CONDITION" readonly="">
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">저장</button>
    </div>
</form>

<script>
    $('#SDETAIL').on('change' , function(e) {
        var s_detail = $('#SDETAIL').val();
        if(s_detail == "SHIPPING") {
            $('#CONDITION').val('고객 전달중')
        }
        else if(s_detail == "ARRANGE") {
            $('#CONDITION').val('회송대기')
        }
        else if(s_detail == "COLLECT") {
            $('#CONDITION').val('회송중')   
        }
        else if(s_detail == "CHECK") {
            $('#CONDITION').val('물품 확인중')      
        }
    })
</script>

@endsection