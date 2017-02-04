@extends('layouts.3dRPC')

@section('head') @parent
    <link rel="stylesheet" type="text/css" href="/css/sortable.css" media="all" />
    <link rel="stylesheet" type="text/css" href="/css/3dRNA/result.css" media="all" />
    <script src="/js/sortable.js"></script>
    <script src="/js/3dRNA/result.js"></script>
@endsection

@section('content-main')
<div class='panel panel-default' style="margin-top:20px">
    <div class='panel-body'>
        <h1>3dRPC-server Results (Task @{{task.job}})</h1>
        <h3 class="jn-title">Task Information:</h3>
        <table class="table table-striped" style="word-break:break-all">
            <tr><td style="min-width:150px">Job ID</td><td>@{{task.job}}</td></tr>
            <tr><td>Email</td><td>@{{task.email}}</td></tr>
            <tr><td>Scoring Functions</td><td>@{{task.sf}}</td></tr>
            <tr><td>Protein strucuture</td><td><a href="/3dRPC/download/@{{task.job}}/p">Download</a></td></tr>
            <tr><td>RNA strucuture</td><td><a href="/3dRPC/download/@{{task.job}}/r">Download</a></td></tr>
            <tr><td>Number of Predictions</td><td>@{{task.num}}</td></tr>
        </table>
 
 <div id="div-status" v-if="task.state=='ERROR'">
    <h3 class='jn-title'>Status:</h3>
        <p>Oops...There must be something wrong with your input!</p>
                </div>






<div id="div-status" v-if="task.state!='finished' && task.state!='ERROR'">
    <h3 class='jn-title'>Status:</h3>
        <p>The server is processing your task. Please wait for a while...</p>
        <p>Task state: @{{task.state}}%</p>
            <div class="progress">
                <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" :aria-valuenow="percentage" aria-valuemin="0" aria-valuemax="100" style="width: @{{task.state}}%">
                <span class="sr-only">@{{task.state}}% Complete</span>
                </div>
                </div>
                </div>




    <div id="div-results" v-if="task.state=='finished'">
        <h3 class='jn-title'>Results:</h3>
            <a href="/3dRPC/download/@{{task.job}}/all">Download All</a>
                <div class="table-responsive">
                    <table class="result sortable" id="table-results">
                    <thead>
                    <tr>
                    <th class="sort-button">Model Name</th>
                    <th class="sort-button sort-num">Energy</th>
                    </tr>
                    </thead>
                        <tbody>
                            <tr v-for="el in task.scores">
                            <td>@{{task.job}}-@{{$index+1}}<small>(<a target="_blank" href="/3dRPC/view/@{{task.job}}/@{{$index+1}}">view</a> | <a href="/3dRPC/download/@{{task.job}}/@{{$index+1}}">download</a>)</small></td>
                            <td>@{{el}}</td>
                            </tr>
                    </tbody>
                    </table>
                </div>
            </div>
    </div>
</div>
@endsection

@section('main-js')
    <script type="text/javascript" src="/js/vue.min.js"></script>
    <script type="text/javascript">
        var v = new Vue({
            el: "#vue-main",
            data: {
                task: {!!$task!!}
            },
        })

        function foo() {
            jian_ajax("GET", "http://biophy.hust.edu.cn/3dRPC/monitor/"+v.task.job, function(response){
                var r = JSON.parse(response)
                v.task.state = r.state
                console.log(response)
                if (r.state == "finished") {
                    jian_ajax("GET", "http://biophy.hust.edu.cn/3dRPC/tasks/"+v.task.job, function(response){
                        console.log(response)
                        v.task = JSON.parse(response)
                    }, function(){})
                } else {
                    setTimeout(foo, 3000)
                }
            }, function(){})
        }

        foo();
    </script>
@endsection

