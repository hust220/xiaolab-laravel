@extends('layouts.layout')

@section('header-top')
<a id="title" href="/3dRNA">CADNAS</a>
@endsection

@section('head') @parent
<link rel="stylesheet" type="text/css" href="{{url('/css/sortable.css')}}" media="all" />
<script src="{{url('/js/sortable.js')}}"></script>
<script src="{{url('/js/pageable.js')}}"></script>
<script src="{{url('/js/CADNAS/show.js')}}"></script>
@endsection

@section('content-main')
    <div class='box'>
        <form METHOD="POST" ACTION="" ENCTYPE="multipart/form-data" target="_blank" onsubmit="return false;">
        <table class='query-table'>
            <tr><th>Type</th><th>PDB ID</th><th>Length</th><th>Sequence</th><th>Number</th></tr>
            <tr><td><select name="type">
                                <option value="dsDNA">dsDNA</option>
                                <option value="pair">base-pair</option>
                                <option value="quadruplex">G-quadruplex</option>
                                <option value="triple">triplex</option>
                                <option value="strand">strand</option>
                                <option value="other">other</option></select></td>
                    <td><input type="text" name="name"/></td>
                    <td><input type="text" name="length" value="12" /></td>
                    <td><input type="text" name="seq"/></td>
                    <td><input type="text" name="num" value="20" /></td>
                    <td><input type="submit" id='submit' value="Query" class='button blue small'/></td></tr></table></form></div>
        
    <div class='box' id='result-panel'>The results would be displayed here!</div>

@endsection
