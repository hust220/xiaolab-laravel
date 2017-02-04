@extends('DCA.layout')

@section('head') @parent
    <style>
        textarea {
            word-break: break-all;
        }

        @keyframes rotate {
            0% {transform: rotate(0deg);}
            25% {transform: rotate(90deg);}
            50% {transform: rotate(180deg);}
            75% {transform: rotate(270deg);}
            100% {transform: rotate(360deg);}
        }

        .refresh {
            animation-name: rotate;
            animation-duration: 1s;
            animation-timing-function: linear;
            animation-delay: 0s;
            animation-iteration-count: infinite;
            animation-play-state: running;
        }
    </style>
@endsection


@section('content-main')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Introduction</h3>
        </div>
        <div class="panel-body">
            Direct Coupling Analysis (DCA) is a statistical inference framework used to infer direct 
            co-evolutionary couplings among residue pairs in multiple sequence alignments, which aims 
            at disentangling direct from indirect correlations.
        </div>
    </div>

     <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">New Task</h3>
        </div>
        <div class="panel-body">
            <form v-el:form1 method="POST" class="form-horizontal" enctype="multipart/form-data" onsubmit="return false;">
                <!-- molecular type -->
                <div class="form-group">
                    <label class="col-sm-offset-1 col-sm-12">Please select a molecular type:</label>
                    <div class="col-sm-offset-1 col-sm-6">
                        <select class="form-control" name="mol_type">
                            <option value="RNA" selected>RNA</option>
                            <!-- <option value="protein">Protein</option> -->
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-offset-1 col-sm-12">
                        Please provide the <a href="#" @click="click_sequence">sequence</a> or the
                                           <a href="#" @click="click_msa">MSA</a>
                    </label>
                </div>

                <!-- sequence -->
                <div class="form-group" v-if="show_sequence">
                    <div class="col-sm-offset-1 col-sm-6">
                        <textarea class="form-control" name="seq" rows=5 v-model="seq" :placeholder="placeholder_seq"></textarea>
                    </div>
                    <div class="col-sm-3">
                        <ul>
                            Example:
                            <li><a @click="seq='GGCGCGUUAACAAAGCGGUUAUGUAGCGGAUUGCAAAUCCGUCUAGUCCGGUUCGACUCCGGAACGCGCCUCCA'">Example 1</a></li>
                            <li><a @click="seq='GGCGCGUUAACAAAGCGGUUAUGUAGCGGAUUGCAAAUCCGUCUAGUCCGGUUCGACUCCGGAACGCGCCUCCA'">Example 2</a></li>
                        </ul>
                    </div>
                </div>


                <!-- msa -->
                <div class="form-group" v-if="show_msa">
                    <div class="col-sm-offset-1 col-sm-6">
                        <textarea name="msa" rows=10 class="form-control" v-model="msa" :placeholder="placeholder_msa"></textarea>
                    </div>

                    <div class="col-sm-2">
                        <ul>
                            Example:<br>
                            <li><a href="#">Example 1</a></li>
                            <li><a href="#">Example 2</a></li>
                        </ul>
                    </div>
                </div>

                <!-- advanced settings -->
                <div class="form-group">
                    <div class="col-sm-offset-1 col-sm-11"><a @click="show_advanced_settings^=true">advanced settings</a></div>
                </div>

                <div class="form-group" v-show="show_advanced_settings">
                    <label class="col-sm-offset-1 col-sm-12">Select the engine for multiple sequences align: </label>
                    <div class="col-sm-offset-1 col-sm-3">
                        <select class="form-control" name="msa_engine" v-model="msa_engine">
                            <option value="muscle">Muscle</option>
                        </select>
                    </div>
                </div>

                <!-- dca button -->
                <div class="form-group">
                    <div class="col-sm-offset-1 col-sm-11">
                        <button @click="submit" class="btn btn-info" :disabled="disable_submit">DCA</button>
                        <button type="reset" class="btn btn-warning">Clear</button>
                    </div>
                </div>
            </form>

            <vue-alert type="danger" :show="show_errors" :contents="errors"></vue-alert>
            <vue-alert type="success" :show="show_results" :contents="results"></vue-alert>
        </div>
    </div>

    <div class='panel panel-default' v-if="running_tasks.length">
        <div class='panel-heading'>
            <h3 class='panel-title'>Submitted Tasks <a @click="get_running_tasks" title="Refresh"><span class="glyphicon glyphicon-refresh @{{rotate_refresh}}"></span></a></h3>
        </div>
        <div class='panel-body'>
            <vue-tasks :tasks="running_tasks" :page=1 :page_size=10 :items="{id:'id',submit_at:'created_at'}" task_type="DCA/task"></vue-tasks>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Query Task</h3>
        </div>
        <div class="panel-body">
            <vue-query></vue-query>
            <vue-alert type="danger" :show="show_query_errors" :contents="query_errors"></vue-alert>
            <template v-if="query_results.length != 0">
                <vue-tasks :tasks="query_results" :page=1 :page_size=10 :items="{id:'id',submit_at:'created_at'}" task_type="DCA/task"></vue-tasks>
            </template>
        </div>
    </div>
@endsection

@section('main-js')
    <script type="text/javascript" src="/js/vue.min.js"></script>
    <script type="text/javascript" src="/js/vue-alert.js"></script>
    <script type="text/javascript">
        var v = new Vue({
            el: '#vue-main',

            data: {
                seq: "",
                msa: "",
                errors: "",
                query_errors: "",
                results: "",
                query_results: [],
                show_errors: false,
                show_query_errors: false,
                show_results: false,
                show_sequence: true,
                show_msa: false,
                disable_submit: false,
                query_results: [],
                show_advanced_settings: false,
                msa_engine: "muscle",
                running_tasks: [],
                rotate_refresh: '',
                ip: "{{$ip}}",
            },

            methods: {
                click_sequence: function(){
                    this.show_sequence = true
                    this.show_msa = false
                },

                click_msa: function(){
                    this.show_sequence = false
                    this.show_msa = true
                },

                submit: function(){
                    var v = this
                    v.disable_submit = true
                    jian_ajax("POST", "http://biophy.hust.edu.cn/DCA", new FormData(v.$els.form1), function(response){
                        var task_id = JSON.parse(response).task_id;
                        v.show_errors = false
                        v.disable_submit = false
                        v.get_running_tasks()
                        jian_newtab("http://biophy.hust.edu.cn/DCA/task/"+task_id);
                    }, function(response){
                        v.errors = JSON.parse(response).errors
                        v.show_errors = true
                        v.disable_submit = false
                    })
                },

                get_running_tasks: function() {
                    var v = this
                    v.rotate_refresh = 'refresh'
                    jian_ajax("GET", "http://biophy.hust.edu.cn/DCA/running_tasks/"+v.ip, function(response){
                        var r = JSON.parse(response)
                        r.sort(function(a, b){
                            return b.created_at-a.created_at
                        })
                        console.log(r)
                        v.running_tasks = r
                        v.rotate_refresh = ''
                    }, function(response){
                        console.log(response)
                        v.rotate_refresh = ''
                    });
                },

            },

            events: {
                "vue-query-submit": function(query, el) {
                    var v = this
                    //v.query_results = []
                    jian_ajax("POST", "http://biophy.hust.edu.cn/DCA/query", new FormData(el), function(response){
                        console.log(response);
                        var r = JSON.parse(response).results
                        r.sort(function(a, b){
                            return b.created_at-a.created_at
                        })
                        v.query_results = r
                        v.show_query_errors = false
                    }, function(response){
                        console.log(response);
                        v.query_results = []
                        v.query_errors = JSON.parse(response).errors
                        v.show_query_errors = true
                    })
                },
            },
        })
        v.get_running_tasks()
    </script>
@endsection


