@extends('layouts.rna')

@section('head') @parent
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" type="text/css" href="/css/introjs.min.css" media="all" />
    <style>
        textarea {
            word-break: break-all;
        }
    </style>
@endsection

@section('content-main')
    @if($method == '')
        <?php $method = 'assemble'; ?>
    @elseif($method == 'dg')
        <script>
            document.title = '3dRNA_DG';
            document.getElementById('title').text='3dRNA_DG: RNA 3D structure prediction by distance geometry';
        </script>
    @endif

    <div class='panel panel-default'>
        <div class='panel-heading visible-xs'>
            <h3 class='panel-title'>3dRNA</h3>
        </div>
        <div class='panel-body'>
            <FORM v-el:form name="form1" method="POST" enctype="multipart/form-data" class='form-horizontal' onsubmit="return false;">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="mol_type" value="RNA">

                <div class='form-group' data-step="1" data-intro="(Optional) Input your email.">
                    <label class="col-sm-2 control-label">Email (optional):</label>
                    <div class="col-sm-4">
                        <input type="text" name="email" class='form-control' v-model="email">
                    </div>
                </div>

                <div class="form-group" data-step="2" data-intro="Input the sequence.">
                    <label class="col-sm-2 control-label">Sequence:</label>
                    <div class="col-sm-6">
                        <textarea name="seq" rows="3" class='form-control' :placeholder="seq_prompt" :title="seq_prompt" v-model="seq" v-on:change="check_seq"></textarea>
                    </div>
                    <div class='col-sm-4'>
                        Examples:
                        <ul>
                            <li><a v-on:click="show_example1">Example 1</a></li>
                            <li><a v-on:click="show_example2">Example 2</a></li>
                            <li><a v-on:click="show_example3">Example 3</a></li>
                        </ul>
                    </div>
                </div>

                <div class='form-group' data-step="3" data-intro="Input the 2D structure.">
                    <label class='col-sm-2 control-label'>2D Structure:</label>
                    <div class='col-sm-6'>
                        <textarea name="ss" rows="3" class='form-control' :placeholder="ss_prompt" :title="ss_prompt" v-model="ss" v-on:change="check_ss"></textarea>
                    </div>
                </div>

                <div data-step="4" data-intro="(Optional) Settings about sampling.">
                    <div class='form-group'>
                        <div class='col-sm-2'></div>
                        <div class='col-sm-4'><a v-on:click="toggle_sampling_settings">Sampling Settings</a></div>
                    </div>

                    <div v-show="show_sampling_settings">
                        <div class='form-group'>
                            <label class='col-sm-3 control-label'>Number of clusters:</label>
                            <div class="col-sm-3">
                                <input name="num" type="text" :placeholder="num_prompt" :title="num_prompt" v-model="num" @change="num_changed" class='form-control'>
                            </div>
                        </div>

                        <div class='form-group'>
                            <label class='col-sm-3 control-label'>Number of sampling:</label>
                            <div class='col-sm-3'>
                                <input type="text" name="num_sampling" :placeholder="num_sampling_prompt" :title="num_sampling_prompt" v-model="num_sampling" @change="num_sampling_changed" class='form-control'>
                            </div>
                        </div>

                        <div class='form-group'>
                            <label class='col-sm-3 control-label'>Constraints:</label>
                            <div class='col-sm-6'>
                                <textarea name="constraints" rows="4" class='form-control' :placeholder="constraints_placeholder" :title="constraints_title" v-model="constraints"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div data-step="5" data-intro="(Optional) Settings about templates.">
                    <div class='form-group'>
                        <div class='col-sm-offset-2 col-sm-4'><a v-on:click="toggle_templates_settings">Templates Settings</a></div>
                    </div>

                    <div v-show="show_templates_settings">
                        <div class='form-group'>
                            <label class='col-sm-3 control-label'>Templates Source:</label>
                            <div class='col-sm-3'>
                                <select name="templates_source" class='form-control'>
                                    <option value='nat_struct'>Native Structures</option>
                                    <option value='DG'>Distance Geometry</option>
                                </select>
                            </div>
                        </div>

                        <div class='form-group'>
                            <label class='col-sm-3 control-label'>Don't use templates of:</label>
                            <div class='col-sm-3'>
                                <input type="text" name="disused_pdbs" v-model="disused_pdbs" placeholder="PDB ID" class='form-control'>
                            </div>
                        </div>
                    </div>
                </div>

                <div data-step="6" data-intro="(Optional) Other settings.">
                    <div class='form-group'>
                        <div class='col-sm-offset-2 col-sm-4'><a v-on:click="toggle_other_settings">Other Settings</a></div>
                    </div>

                    <div v-show="show_other_settings">
                        <div class='form-group'>
                            <div class='col-sm-offset-3 col-sm-4'>
                                <div class='checkbox'>
                                    <label><input type='checkbox' name='en_min' checked>Energy minimization</label>
                                </div>
                            </div>
                        </div>

                        <div class='form-group' style="display:none">
                            <div class='col-sm-offset-3 col-sm-4'>
                                <div class='checkbox'>
                                    <label><input type='checkbox' name='compute_score' checked>Compute 3dRNAscore</label>
                                </div>
                            </div>
                        </div>

                        <div class='form-group'>
                            <label class='col-sm-3 control-label'>Seed:</label>
                            <div class="col-sm-3">
                                <input name="seed" type="text" v-model="seed" class='form-control'>
                            </div>
                        </div>
                    </div>
                </div>

                <div class='form-group'>
                    <div class='col-sm-offset-2 col-sm-3'>
                        <button class="btn btn-info" @click="submit" data-step="7" data-intro="Submit the task.">Submit</button>
                        <button type="reset" class="btn btn-warning">Clear</button>
                    </div>
                </div>

            </form>

            <div class='row'>
                <small class='col-sm-offset-2 col-sm-12'>
                    <em>Get help from the the <a href="#" v-on:click="show_guide" >step-by-step guid</a> or the
                                              <a href="http://biophy.hust.edu.cn/download/Users'%20Manual.pdf">documentation</a> of 3dRNA
                    </em>
                </small>
            </div>

            <div class="alert alert-danger" v-if="show_errors">
                <button type="button" class="close" @click="show_errors = false">
                    <span>&times;</span>
                </button>
                <ul>
                    <li v-for="error in errors">
                        @{{{error}}}
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class='panel panel-default'>
        <div class='panel-body'>
            <form class='form-horizontal' method='post' action='/3dRNA/jobs' name='form2' target='_blank'>
            @include('query')
            </form>
        </div>
    </div>

    <div class='panel panel-default'>
        <div class='panel-body'>
            <div class='row'>
                <div class='col-sm-offset-2 col-sm-8'>
                    <img class="image-responsive" src="/image/pic.jpg" width=80% >
                </div>
            </div>
            <div class='row'>
                <div class='col-sm-offset-2 col-sm-8'>
                    <p>The prediction result (blue) is superimposed on its respective experimental structure (gold).</p>
                </div>
            </div>
        </div>
    </div>

    <div class='panel panel-default'>
        <div class='panel-body'>
            References: <br />
            <ol>
                <li>
                    Zhao, Y., et al.,
                    <a href="http://biophy.hust.edu.cn/download/3dRNA.pdf">
                        Automated and fast building of three-dimensional RNA structures
                    </a>.
                    Scientific Reports, 2012. 2: p. 734.
                </li>
                <li>
                    Wang, J., et al.,
                    <a href="http://biophy.hust.edu.cn/download/3dRNAscore.pdf">
                        3dRNAscore: a distance and torsion angle dependent evaluation function of 3D RNA structures
                    </a>.
                    Nucleic Acids Res, 2015. 43(10): p. e63.
                </li>
            </ol>
        </div>
    </div>
@endsection

@section('main-js')
    <script type="text/javascript" src="/js/intro.min.js"></script>
    <script type="text/javascript" src="/js/vue.min.js"></script>
    <script type="text/javascript" src="/js/vue-resource.min.js"></script>
    <script type="text/javascript">
        var state = {
            email: "",
            seq: "",
            ss: "",
            constraints: "",
            num: 5,
            num_sampling: 1000,
            disused_pdbs: "",
            seed: 11,
            errors: '',
            show_sampling_settings: false,
            show_templates_settings: false,
            show_other_settings: false,
            show_errors: false,
        };

        var example1 = {
            email: "",
            seq: 'UCGGCGGUGGGGGAGCAUCUCCUGUAGGGGAGAUGUAACCCCCUUUACCUGCCGAACCCCGCCAGGCCCGGAAGGGAGCAACGGUAGGCAGGACGUCGG',
            ss:  '(((.((.((((((..(((((((......)))))))...))))))...((((((.....(((.....(((....))).....)))..)))))).)).)))',
            constraints: "",
            num: 5,
            num_sampling: 1000,
            disused_pdbs: "",
            seed: 11,
            show_sampling_settings: false,
            show_templates_settings: false,
            show_other_settings: false,
        };

        var example2 = {
            email: "",
            seq: 'UCCGGUGACUCCGGAGAAACAAAGUCA',
            ss:  '(((((.[[[.)))))........]]].',
            constraints: "",
            num: 5,
            num_sampling: 1000,
            disused_pdbs: "",
            seed: 11,
            show_sampling_settings: false,
            show_templates_settings: false,
            show_other_settings: false,
        };

        var example3 = {
            email: "wj_hust08@hust.edu.cn",
            seq: 'UCCGGUGACUCCGGAGAAACAAAGUCA',
            ss:  '(((((.....)))))............',
            constraints: "7 26\n8 25\n9 24",
            num: 5,
            num_sampling: 1000,
            disused_pdbs: "1Y26 2GDI 3Q3Z",
            seed: 11,
            show_sampling_settings: true,
            show_templates_settings: false,
            show_other_settings: false,
        };

        var info = {
            seq_prompt: "Please input the sequence here. Only the 4 characters 'A', 'U', 'G', 'C' are accepted.",
            ss_prompt: "Please input the 2D structure with dot-bracket form in here.",
            num_prompt: "Integer between 1 and 10",
            num_sampling_prompt: "Integer between 1 and 1000",
            constraints_placeholder: "Hover your mouse over this to see the example.",
            constraints_title: "Example:\n7 26\n8 25\n9 24\n10 23\n11 22",
        };

        var vue = new Vue({
            el: "#vue-main",
            data: merge_objects(state, info),

            methods: {
                submit: function() {
                    var v = this;
                    jian_ajax("POST", "http://biophy.hust.edu.cn/3dRNA/submit", new FormData(v.$els.form), function (response) {
                        console.log("ok");
                        var task_id = JSON.parse(response).task_id;
                        jian_newtab("http://biophy.hust.edu.cn/3dRNA/result/"+task_id);
                    }, function (response) {
                        v.errors = JSON.parse(response).errors;
                        v.show_errors = true;
                    });
                },

                push_state: function() {
                    for (var el in state) {
                        state[el] = this[el];
                    }
                },

                pop_state: function() {
                    for (var el in state) {
                        this[el] = state[el];
                    }
                },

                set_state: function(s) {
                    for (var el in state) {
                        this[el] = s[el];
                    }
                },

                toggle_sampling_settings: function(e) { this.show_sampling_settings ^= true; },

                toggle_templates_settings: function(e) { this.show_templates_settings ^= true; },

                toggle_other_settings: function(e) { this.show_other_settings ^= true; },

                clear: function() {
                    for (var el in state) {
                        if (typeof(state[el]) != "boolean") {
                            this[el] = "";
                        }
                    }
                },

                show_guide: function(e) {
                    this.push_state();
                    this.show_sampling_settings = this.show_templates_settings = this.show_other_settings = true;
                    this.clear();
                    var v = this;
                    introJs().oncomplete(function(){
                        v.pop_state();
                    }).onafterchange(function(e){
                        var intro = this;
                        var n = parseInt(e.getAttribute("data-step"));
                        setTimeout(function(){
                            if (n == 1) {
                                v.email = example3.email;
                            } else if (n == 2) {
                                v.seq = example3.seq;
                            } else if (n == 3) {
                                v.ss = example3.ss;
                            } else if (n == 4) {
                                v.num = example3.num;
                                v.num_sampling = example3.num_sampling;
                                v.constraints = example3.constraints;
                            } else if (n == 5) {
                                v.disused_pdbs = example3.disused_pdbs;
                            } else if (n == 6) {
                                v.seed = example3.seed;
                            }
                        }, 1500);
                        setTimeout(function(){intro.nextStep();}, 3000);
                    }).setOption("showButtons", false).start();
                },

                show_example1: function(e) {
                    this.set_state(example1);
                },

                show_example2: function(e) {
                    this.set_state(example2);
                },

                show_example3: function(e) {
                    this.set_state(example3);
                },

                check_seq: function() {
                    this.seq = this.seq.replace(/\s+/g, '').toUpperCase();
                },

                check_ss: function() {
                    this.ss = this.ss.replace(/\s+/g, '');
                },

                num_changed: function(e) {
                    if (isNaN(this.num)) {
                        alert(this.num_prompt);
                        this.num = 5;
                    } else {
                        if (this.num > 10) this.num = 10;
                        else if (this.num < 1) this.num = 1;
                        this.num = parseInt(this.num);
                    }
                },

                num_sampling_changed: function(e) {
                    if (isNaN(this.num_sampling)) {
                        alert(this.num_sampling_prompt);
                        this.num_sampling = 1000;
                    } else {
                        if (this.num_sampling > 1000) this.num_sampling = 1000;
                        else if (this.num_sampling < 1) this.num_sampling = 1;
                        this.num_sampling = parseInt(this.num_sampling);
                    }
                },

            }
        });
    </script>
@endsection

