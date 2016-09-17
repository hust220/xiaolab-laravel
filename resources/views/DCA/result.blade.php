@extends('layouts.rna')

@section('title', 'DCA')

@section('head') @parent
<link rel="stylesheet" type="text/css" href="{{url('/css/sortable.css')}}" media="all" />
<link rel="stylesheet" type="text/css" href="{{url('/css/DCA/result.css')}}" media="all" />
<script src="{{url('/js/sortable.js')}}"></script>
@endsection

@section('header-top')
<a id="title" href="{{url('DCA')}}">DCA Results</a>
@endsection

@section('content-main')
<div class='box'>
@if(isset($results))
    <p>Download: <a href="{{url("DCA/download/$id")}}">{{"DCA-result-$id.txt"}}</a></p>
    <table class="result sortable table table-stripped">
    <thead><tr><th class="sort-button sort-num">N1</th><th class="sort-button sort-num">N2</th>
               <th class="sort-button sort-num">MI</th><th class="sort-button sort-num">DI</th></tr></thead>
    <tbody>
    @for($i = 0; $i < count($results); $i++)
        <tr>
        @for($j = 0; $j < 4; $j++)
            <td>{{$results[$i][$j]}}</td>
        @endfor
        </tr>
    @endfor
    </tbody>
    </table>
@else
    <p>Please wait for a while...</p><img id="loading-img" src="/images/loading.gif"/>
    <script type="text/javascript">setTimeout('location.href="{{url('DCA/result/'.$id)}}"', 3000);</script>
@endif
</div> <!-- box --!>
@endsection


