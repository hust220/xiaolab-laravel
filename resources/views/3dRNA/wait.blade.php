@extends('layouts.rna')

@section('head') @parent
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/css/wait.css') }}" media="all" />
@endsection

@section('content-main')
<div class='box'>
<h1>3dRNA-server Results (Job {{$job}})</h1>
<h3>Job Information:</h3>
<table class="table table-striped">
<tr><td>Job ID</td><td>{{$job}}</td></tr>
<tr><td>Email</td><td>{{$email}}</td></tr>
<tr><td>Submit Time</td><td>{{date("Y-m-d H:i:s", $submit_time)}}</td></tr>
<tr><td>Finish Time</td><td>...</td></tr>
<tr><td>Sequence</td><td>{{$seq}}</td></tr>
<tr><td>2D Structure</td><td>{{$ss}}</td></tr>
<tr><td>Number</td><td>{{$num}}</td></tr>
<tr><td>Method</td><td>{{strtoupper($method)}}</td></tr>
</table>
<div id="wait-div"><span id="wait-info"><b>The job has been submitted. Please wait for a while.</b></span><img id="loading-img" src="/images/loading.gif"/></div>
<script type="text/javascript">setTimeout('location.href="{{url('3dRNA/wait/'.$job)}}"', 3000);</script>
</div>
@endsection

