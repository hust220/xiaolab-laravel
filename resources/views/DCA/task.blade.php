@extends('DCA.layout')

@section('content-main')
    <div class="panel panel-default">
        <div class="panel-heading">
            <button type="button" class="close" @click="show = false"> 
                <span>&times;</span> 
            </button> 
            <h3 class="panel-title">Task @{{task.id}}</h3>
        </div>
        <div class="panel-body">
            <p>Sequence: @{{task.seq}}</p>
            <p>State: @{{task.state}}</p>
            <vue-progress :percentage="percentage" v-if="!is_finished(task.state)"></vue-progress>
            <p>Family in Rfam: <a href="http://rfam.xfam.org/family/@{{task.rfam}}" target="_blank">@{{task.rfam}}</a></p>
            <p>Downloads: <a href="/DCA/download/@{{task.id}}/msa" v-if="is_msa_generated(task.state)">MSA file</a>&nbsp;&nbsp;&nbsp;
                          <a href="/DCA/download/@{{task.id}}/di" v-if="is_finished(task.state)">DI file</a></p>
            <template v-if="is_finished(task.state)">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Residue 1</th>
                            <th>Residue 2</th>
                            <th>MI</th>
                            <th>DI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in task.result">
                            <th v-text="item[0]"></th>
                            <th v-text="item[1]"></th>
                            <th v-text="item[2]"></th>
                            <th v-text="item[3]"></th>
                        </tr>
                    </tbody>
                </table>
            </template>
        </div>
    </div>
@endsection

@section('main-js')
    <script type="text/javascript" src="/js/vue.min.js"></script>
    <script type="text/javascript" src="/js/vue-alert.js"></script>
    <script type="text/javascript">
        var states = ['Query Rfam', 'MSA', 'DCA', 'Delete gaps', 'Finished'];
        var v = new Vue({
            el: '#vue-main',
            data: {
                task: {
                    id: {{$task['id']}},
                    state: "",
                    rfam: "",
                    seq: "{{$task['seq']}}",
                    msa: "",
                    result: [],
                },
            },
            computed: {
                percentage: function() {
                    return (states.indexOf(this.task.state)+1)/states.length*100;
                },
            },
            methods: {
                is_rfam_found: function (state) {
                    return states.indexOf(state) >= states.indexOf('MSA')
                },

                is_msa_generated: function (state) {
                    return states.indexOf(state) >= states.indexOf('DCA')
                },

                is_finished: function (state) {
                    return state == 'Finished'
                },
            },
        })
        function foo() {
            jian_ajax("GET", "http://biophy.hust.edu.cn/DCA/monitor/"+v.task.id, function(response){
                var r = JSON.parse(response);
                console.log(r);
                v.task.state = r.state;
                if (v.is_rfam_found(r.state)) v.task.rfam = r.rfam;
                if (v.is_finished(r.state)) {
                    jian_ajax("GET", "http://biophy.hust.edu.cn/DCA/result/"+v.task.id, function(response){
                        v.task.result = JSON.parse(response);
                        console.log(v.task);
                    }, function(){});
                } else {
                    setTimeout(foo, 3000);
                }
            }, function(){});
        }
        foo()
    </script>
@endsection


