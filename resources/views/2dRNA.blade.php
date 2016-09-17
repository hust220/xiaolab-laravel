@extends('layouts.rna')

@section('title', '2dRNA')

@section('head') @parent
<link rel="stylesheet" type="text/css" href="{{url('/css/RNA2D.css')}}" media="all" />
<script src="{{url('/js/RNA2D.js')}}"></script>
@endsection

@section('header-top')
<a id="title" href="{{url('2dRNA')}}">2dRNA: ncRNA 2D structures prediction with DCA</a>
@endsection

@section('content-main')
<div id='introduction' class='box'>
Introduction:
</div>

<div class='box'>

<form name="form1" method="POST" action="" enctype="multipart/form-data" target="_blank">
<table>
    <tr>
        <td>Input the sequence:<br />
        <textarea name="seq" id='seq' onchange="this.value = this.value.replace(/\s+/g, ''); this.value = this.value.toTpperCase();" placeholder="Example: GGCGUAAGGAUUACCUAUGCC" size="20" rows="5" cols="50"></textarea></td>
    </tr>
    <tr>
        <td><input type="submit" name="submit" value="Submit" class="btn btn-primary btn-xs">
        <input type="reset" id="clear" value="Clear" class="btn btn-warning btn-xs"></td>
    </tr>
</table>

<a href="javascript:;">+Advanced options:</a>

<div>
cutoff:<input type='text' value='1' />
</div>
</form> <!-- form1 --!>

</div> <!-- box --!>
@endsection


