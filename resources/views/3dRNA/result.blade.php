@extends('layouts.rna')

@section('head') @parent
    <link rel="stylesheet" type="text/css" href="/css/sortable.css" media="all" />
    <link rel="stylesheet" type="text/css" href="/css/3dRNA/result.css" media="all" />
    <script src="/js/sortable.js"></script>
    <script src="/js/3dRNA/result.js"></script>
@endsection

@section('content-main')
<div class='panel panel-default' style="margin-top:20px">
    <div class='panel-body'>
        <h1>3dRNA-server Results (Task @{{task.job}})</h1>
        <h3 class="jn-title">Task Information:</h3>
        <table class="table table-striped" style="word-break:break-all">
            <tr><td style="min-width:150px">Job ID</td><td>@{{task.job}}</td></tr>
            <tr><td>Email</td><td>@{{task.email}}</td></tr>
            <tr><td>Sequence</td><td>@{{task.seq}}</td></tr>
            <tr><td>2D Structure</td><td>@{{task.ss}}</td></tr>
            <tr><td>Molecular Type</td><td>@{{task.mol_type}}</td></tr>
            <tr><td>Number of Clusters</td><td>@{{task.num}}</td></tr>
            <tr><td>Constraints</td><td>@{{task.constraints}}</td></tr>
            <tr><td>Seed</td><td>@{{task.seed}}</td></tr>
        </table>
        
        <div id="div-results" v-if="task.state=='finished'">
            <h3 class='jn-title'>Results:</h3>
            <a href="/3dRNA/download/@{{task.job}}/all">Download All</a>
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
                            <td>@{{task.job}}-@{{$index+1}}<small>(<a target="_blank" href="/3dRNA/view/@{{task.job}}/@{{$index+1}}">view</a> | <a href="/3dRNA/download/@{{task.job}}/@{{$index+1}}">download</a>)</small></td>
                            <td>@{{el}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="div-status" v-if="task.state!='finished'">
            <h3 class='jn-title'>Status:</h3>
            <p>The server is processing your task. Please wait for a while...</p>
            <p>Task state: @{{task.state}}</p>
            <div class="progress">
              <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" :aria-valuenow="percentage" aria-valuemin="0" aria-valuemax="100" style="width: @{{percentage}}%">
                  <span class="sr-only">@{{percentage}}% Complete</span>
                </div>
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
            computed: {
                percentage: function() {
                    return (this.task.states.indexOf(this.task.state)+1)/this.task.states.length*100
                },
            },
        })

        function foo() {
            jian_ajax("GET", "http://biophy.hust.edu.cn/3dRNA/monitor/"+v.task.job, function(response){
                var r = JSON.parse(response)
                v.task.state = r.state
                console.log(response)
                if (r.state == "finished") {
                    jian_ajax("GET", "http://biophy.hust.edu.cn/3dRNA/tasks/"+v.task.job, function(response){
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

