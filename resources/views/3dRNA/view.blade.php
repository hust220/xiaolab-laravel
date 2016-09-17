@extends('layouts.rna')

@section('head') @parent
<link rel="stylesheet" type="text/css" href="/css/3dRNA/view.css" media="all" />
<script type="text/javascript" src="/js/JSmol.min.js"></script>
@endsection

@section('content-main')
<div class='panel panel-default' style='margin-top:20px'>
    <div class="panel-body">
        <div id='panel' class='row'>
            <div id="inf-panel" class='col-lg-5'>
                <table>
                <tr><td>Model Name</td><td><a href="{{"/3dRNA/download/$job/$index"}}" target="_blank">{{$name}}</a></td></tr>
                <tr><td>Sequence</td><td>{{$seq}}</td></tr>
                <tr><td>2D Structure</td><td>{{$ss}}</td></tr>
                </table>
            </div> <!-- inf-panel --!>

            <div id="show-panel" class='col-lg-5'>
                <script type="text/javascript">
                var info = {
                    color: "#000000",
                    height: 500,
                    width: 500,
                    src: "{{"/3dRNA/download/$job/$index"}}",
                    j2sPath: "/js/j2s",
                    use: "HTML5"
                };
                Jmol.getApplet("myJmol", info);
                </script>
            </div>
        </div>
    </div>
</div>
@endsection

