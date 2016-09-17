@extends('layouts.rna')

@section('title', 'DCA')

@section('head') @parent
@endsection

@section('header-top')
        <a id="title" href="{{url('DCA')}}">DCA: Direct coupling analysis</a>
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
        <h3 class="panel-title">Task submission</h3>
    </div>
    <div class="panel-body">
        <form v-el:form1 method="POST" class="form-horizontal" enctype="multipart/form-data" onsubmit="return false;">
            <!-- molecular type -->
            <div class="form-group">
                <label class="col-sm-offset-1 col-sm-12">Please select a molecular type:</label>
                <div class="col-sm-offset-1 col-sm-6" name="mol_type">
                    <select class="form-control">
                        <option value="RNA" selected>RNA</option>
                        <option value="protein">Protein</option>
                    </select>
                </div>
            </div>

            <!-- sequence -->
            <div class="form-group">
                <label class="col-sm-offset-1 col-sm-12">Please input the sequence:</label>
                <div class="col-sm-offset-1 col-sm-6">
                    <input class="form-control" name="seq" type="text" v-model="seq">
                </div>
            </div>

            <!-- get msa button -->
            <div class="form-group">
                <div class="col-sm-offset-1 col-sm-11">
                    <button @click="get_msa" class="btn btn-info" :disabled="seq == ''">Get MSA</button>
                    <button type="reset" class="btn btn-warning">Clear</button>
                </div>
            </div>
 
            <vue-alert type="danger" :show="show_msa_errors" :contents="errors"></vue-alert>
        </form>

        <form v-el:form2 method="POST" class="form-horizontal" enctype="multipart/form-data" onsubmit="return false;">
            <!-- msa -->
            <div class="form-group">
                <label class="col-sm-offset-1 col-sm-12">Provide the MSA (Multiple Sequence Alignment):</label>
                <div class="col-sm-offset-1 col-sm-6">
                    <textarea name="msa" rows=20 class="form-control" v-model="msa"></textarea>
                </div>

                <div class="col-sm-2">
                    <ul>
                        Example:<br>
                        <li><a href="#">Example 1</a></li>
                        <li><a href="#">Example 2</a></li>
                    </ul>
                </div>
            </div>

            <!-- dca button -->
            <div class="form-group">
                <div class="col-sm-offset-1 col-sm-11">
                    <button @click="dca" class="btn btn-primary" :disabled="msa==''">DCA</button>
                    <button type="reset" class="btn btn-warning">Clear</button>
                </div>
            </div>
                
            <!-- advanced options -->
            <div class="row">
                <div class="col-sm-offset-1 col-sm-6">
                    <a href="javascript:;">Advanced options:</a>
                </div>
            </div>
        </form>

        <vue-alert type="danger" :show="show_dca_errors" :contents="errors"></vue-alert>
        <vue-alert type="success" :show="show_results" :contents="results"></vue-alert>
    </div>
</div>
@endsection

@section('main-js')
<script type="text/javascript" src="/js/vue.min.js"></script>
<script type="text/javascript" src="/js/vue-alert.js"></script>
<script type="text/javascript">
    var v = new Vue({
        el: "#vue-main",
        data: {
            seq: "",
            msa: "",
            errors: "",
            results: "",
            show_msa_errors: false,
            show_dca_errors: false,
            show_results: false,
        },
        methods: {
            get_msa: function(){
                var v = this
                jian_ajax("POST", "http://biophy.hust.edu.cn/DCA/msa", new FormData(v.$els.form1), function(response){
                    var r = JSON.parse(response)
                    console.log(r)
                    v.msa = "ok"
                    v.show_msa_errors = false
                }, function(response){
                    v.errors = JSON.parse(response).errors
                    v.show_msa_errors = true
                })
            },

            dca: function(){
                var v = this
                jian_ajax("POST", "http://biophy.hust.edu.cn/DCA/dca", new FormData(v.$els.form2), function(response){
                    v.results = JSON.parse(response).result.join("\n")
                    v.show_results = true
                    v.show_dca_errors = false
                }, function(response){
                    v.errors = JSON.parse(response).errors
                    v.show_results = false
                    v.show_dca_errors = true
                })
            },
        },
    })
</script>
@endsection


