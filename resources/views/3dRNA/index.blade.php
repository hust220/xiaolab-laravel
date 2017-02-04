@extends('layouts.rna')

@section('head') @parent
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="stylesheet" type="text/css" href="/css/introjs.min.css" media="all" />
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
@if($method == 'DG')
<script>
   document.title = '3dRNA_DG'
   document.getElementById('title').text='3dRNA_DG: RNA 3D structure prediction by distance geometry'
</script>
@endif

<div id="new-task">
   <div class='panel panel-default'>
      <modal :show.sync="show_modal_success">
         <h3 slot="header">Task submitted successfully!</h3>
         <h3 slot="body">
         <a :href="'http://biophy.hust.edu.cn/3dRNA/result/'+task_id">View the task</a>&nbsp;&nbsp;&nbsp;
         <a @click="show_modal_success=false">Submit new task</a>
         </h3>
      </modal>

      <modal :show.sync="show_modal_fail">
         <h3 style="color:red" slot="header">Task submitted failed!</h3>
         <h3 slot="body">
         <p v-for="el in errors" v-html="el"></p>
         </h3>
         <h3 slot="footer">
         <a @click="show_modal_fail=false">Return to check</a>
         </h3>
      </modal>

      <div class='panel-heading'>
         <h3 class='panel-title'>New Task</h3>
      </div>
      <div class='panel-body'>
         <FORM ref="form1" name="form1" method="POST" enctype="multipart/form-data" class='form-horizontal' onsubmit="return false;">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="mol_type" value="RNA">

            <div class='form-group'>
               <label class="col-sm-2 control-label">Task type:</label>
               <div class='col-sm-4'>
                  <select name="task_type" class='form-control' v-model="task_type">
                     <option v-for="t in task_types" :value="t.type" v-text="t.heading" :selected="t.type=='a'"></option>
                  </select>
               </div>
            </div>

            <div class='form-group' data-step="1" data-intro="(Optional) Input your email.">
               <label class="col-sm-2 control-label">Email (optional):</label>
               <div class="col-sm-4">
                  <input type="text" name="email" class='form-control' v-model="email" :placeholder="email_prompt" :title="email_prompt">
               </div>
            </div>

            <div class='form-group' v-if="task_type=='o'">
               <label class="col-sm-2 control-label">Initial Structure:</label>
               <div class='col-sm-6'>
                  <textarea name="init_struct" rows="5" class='form-control' :placeholder="init_struct_prompt" :title="init_struct_prompt" v-model="init_struct"></textarea>
               </div>
               <div class="col-sm-offset-2 col-sm-6">
                  <table><tr><td>Or upload the structure file: </td><td>
                           <input name="init_struct_file" type="file">
                  </td></tr></table>
               </div>
            </div>

            <div class="form-group" data-step="2" data-intro="Input the sequence." v-if="task_type!='o'">
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

            <!-- 2D structure -->
            <div class='form-group' data-step="3" data-intro="Input the 2D structure.">
               <label class='col-sm-2 control-label'>2D Structure:</label>
               <div class='col-sm-6'>
                  <textarea name="ss" rows="3" class='form-control' :placeholder="ss_prompt" :title="ss_prompt" v-model="ss" v-on:change="check_ss"></textarea>
               </div>
            </div>

            <!-- number -->
            <div class='form-group' v-if="task_type.indexOf('a') >= 0">
               <label class='col-sm-2 control-label' v-text="task_type.indexOf('s') >= 0 ? 'Number of Clusters:' : 'Number:'">Number:</label>
               <div class="col-sm-2">
                  <jn-integer name="num" v-model="num" min="1" max="10"></jn-integer>
               </div>
            </div>

            <div class='form-group' v-if="task_type.indexOf('s') >= 0">
               <label class='col-sm-2 control-label'>Number of Samplings:</label>
               <div class='col-sm-3'>
                  <input type="text" name="num_sampling" :placeholder="num_sampling_prompt" :title="num_sampling_prompt" v-model="num_sampling" @change="num_sampling_changed" class='form-control'>
               </div>
            </div>

            <!-- constraints -->
            <div data-step="4" data-intro="Input the constraints." v-if="task_type.indexOf('o')>=0">
               <div class='form-group'>
                  <label class='col-sm-2 control-label'>Constraints (optional):</label>
                  <div class='col-sm-4'>
                     <div class="input-group">
                        <div class="input-group-addon">Constraints Type</div>
                        <select name="constraints_type" class='form-control' v-model="constraints_type">
                           <option value='dca'>DCA</option>
                           <option value='distances'>Distances</option>
                        </select>
                     </div>
                  </div>
               </div>

               <div class='form-group'>
                  <div class='col-sm-offset-2 col-sm-4'>
                     <textarea name="constraints" rows="4" class='form-control' :placeholder="constraints_placeholder"
                        :title="constraints_title" v-model="constraints"></textarea>
                  </div>
               </div>

               <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-12">
                     <table><tr><td>Or upload the constraints file: </td><td>
                              <input name="constraints_file" type="file" id="constraints_file">
                     </td></tr></table>
                  </div>
               </div>
            </div>

            <div data-step="5" data-intro="(Optional) Settings about templates." v-if="task_type.indexOf('a')>=0">
               <div class='form-group'>
                  <div class='col-sm-offset-2 col-sm-4'><a v-on:click="show_templates_settings^=true">Templates Settings</a></div>
               </div>

               <div v-show="show_templates_settings">
                  <div class='form-group'>
                     <label class='col-sm-3 control-label'>Templates Source:</label>
                     <div class='col-sm-3'>
                        <select name="templates_source" class='form-control' v-model="templates_source">
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

            <!--
            <div data-step="6" data-intro="(Optional) Settings about sampling." v-if="task_type.indexOf('a')>=0">
               <div class='form-group'>
                  <div class='col-sm-2'></div>
                  <div class='col-sm-4'><a v-on:click="show_sampling_settings^=true">Sampling Settings</a></div>
               </div>

               <div v-show="show_sampling_settings">
                  <div class='form-group'>
                     <label class='col-sm-3 control-label'>Sampling:</label>
                     <div class="col-sm-3">
                        <jn-switch name="sample" v-model="sample"></jn-switch>
                     </div>
                  </div>

               </div>
            </div>
            -->

            <div data-step="7" data-intro="(Optional) Other settings.">
               <div class='form-group'>
                  <div class='col-sm-offset-2 col-sm-4'><a v-on:click="show_other_settings^=true">Other Settings</a></div>
               </div>

               <div v-show="show_other_settings">
                  <div class='form-group'>
                     <label class='col-sm-3 control-label'>Energy minimization:</label>
                     <div class="col-sm-3">
                        <jn-switch name="en_min" v-model="en_min"></jn-switch>
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
                  <button class="btn btn-info" @click="submit" data-step="8" data-intro="Submit the task." :disabled="disable_submit_button " v-text="text_submit_button">Submit</button>
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
      </div>
   </div>

   <div class='panel panel-default' v-if="running_tasks.length >0">
      <div class='panel-heading'>
         <h3 class='panel-title'>Submitted Tasks <a @click="get_running_tasks" title="Refresh"><span :class="'glyphicon glyphicon-refresh '+rotate_refresh"></span></a></h3>
      </div>
      <div class='panel-body'>
         <tasks :tasks="running_tasks" :page=1 :page_size=10 :items="{id:'num',submit_at:'submit_time'}" task_type="3dRNA/result"></tasks>
      </div>
   </div>
</div>

<div class="panel panel-default" id="query-task">
   <div class="panel-heading">
      <h3 class="panel-title">Query Task</h3>
   </div>
   <div class="panel-body">
      <query @submitquery="submit"></query>
      <template v-if="query_results.length != 0">
         <tasks :tasks="query_results" :page=1 :page_size=10 :items="{id:'num',submit_at:'submit_time'}" task_type="3dRNA/result"></tasks>
      </template>
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
   <div class='panel-heading'>
      <h3 class='panel-title'>References</h3>
   </div>
   <div class='panel-body'>
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

<script type="text/javascript" src="/js/intro.min.js"></script>
<script type="text/javascript" src="/js/utils.js"></script>
@include('utils.vue')
@include('jian-ui')
@include('utils.query')
@include('utils.tasks')
<script type="text/javascript" src="/js/vue-alert.js"></script>

<script type="text/javascript">
   var state = {
      email: "",
      seq: "",
      ss: "",
      constraints: "",
      num: 1,
      num_sampling: 1000,
      templates_source: "{{$method}}",
      disused_pdbs: "",
      seed: 11,
      errors: '',
      task_id: '',
      init_struct: '',
      show_modal_success: false,
      show_modal_fail: false,
      show_sampling_settings: false,
      show_templates_settings: false,
      show_other_settings: false,
      show_errors: false,
      running_tasks: [],
      ip: "{{$ip}}",
      disable_submit_button: false,
      text_submit_button: "Submit",
      rotate_refresh: '',
      tasks_items: {id:'num'},
      constraints_type: 'dca',
      en_min: true,
      sample: false,
      task_type: 'a',
      task_types: [
         {
            type: 'a',
            heading: '3dRNA (fast, no sampling, no optimization)',
            text: '\
               Assembly module of 3dRNA. First, 3dRNA will decompose the 2D structure into SSEs.\
               Then, 3dRNA will search the whole templates library to find a 2D structure matched 3D template for each SSE.\
               After that, 3dRNA will compose the templates of all the SSEs to an integral 3D structure.',
         },
         {
            type: 'as',
            heading: '3dRNA (medium, sampling, no optimization)',
            text: '\
               Assembly and Sampling module of 3dRNA. After assembly, 3dRNA will sample the templates of all the SSEs.\
               The user can set the number of sampling models.',
         },
         {
            type: 'ao',
            heading: '3dRNA with optimization (slow, sampling, optimization)',
            text: '\
               Integral 3dRNA pipeline. The three modules of 3dRNA (assembly, sampling and optimization) will be executed sequentially.',
         },
         {
            type: 'o',
            heading: 'Optimization',
            text: '\
               Optimization module of 3dRNA. The optimization module uses the method of simulated annealing monte carlo algorithm.\
               During the optimization, the 2D structure will not be changed.',
         },
      ],
   }

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
   }

   var example2 = {
      email: "wj_hust08@hust.edu.cn",
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
   }

   var example3 = {
      email: "wj_hust08@hust.edu.cn",
      seq: 'UCCGGUGACUCCGGAGAAACAAAGUCA',
      ss:  '(((((.....)))))............',
      constraints: "7 26\n8 25\n9 24",
      num: 5,
      num_sampling: 1000,
      disused_pdbs: "1Y26 2GDI 3Q3Z",
      seed: 11,
      show_sampling_settings: false,
      show_templates_settings: false,
      show_other_settings: false,
   }

   var info = {
      init_struct_prompt: "Please paste the content of the structure file here.",
      email_prompt: "Please input your email here to receive results.",
      seq_prompt: "Please input the sequence here. Only the 4 characters 'A', 'U', 'G', 'C' are accepted.",
      ss_prompt: "Please input the 2D structure with dot-bracket form in here.",
      num_prompt: "Integer between 1 and 10",
      num_sampling_prompt: "Integer between 1 and 1000",
      constraints_placeholder: "Input the constraints here. Hover your mouse over this to see the example.",
      constraints_title: "Example:\n7 26\n8 25\n9 24\n10 23\n11 22",
   }

   var v = new Vue({
      el: "#new-task",
      data: merge_objects(state, info),

      methods: {
         submit: function() {
            var v = this
            v.disable_submit_button = true
            v.text_submit_button = "submitting..."
            jian_ajax("POST", "http://biophy.hust.edu.cn/3dRNA/submit", new FormData(v.$refs.form1), function (response) {
               console.log(response)
               v.task_id = JSON.parse(response).task_id
               v.disable_submit_button = false
               v.text_submit_button = "Submit"
               v.get_running_tasks()
               v.show_modal_success = true
            }, function (response) {
               console.log('hi ')
               console.log(response)
               v.errors = JSON.parse(response).errors
               v.disable_submit_button = false
               v.text_submit_button = "Submit"
               v.show_modal_fail = true
            })
         },

         push_state: function() { for (var el in state) state[el] = this[el] },

         pop_state: function() { for (var el in state) { this[el] = state[el] } },

         set_state: function(s) { for (var el in s) { this[el] = s[el] } },

         clear: function() {
            for (var el in example1) {
               if (typeof(state[el]) != "boolean") {
                  this[el] = ""
               }
            }
         },

         show_guide: function(e) {
            this.push_state()
            this.show_sampling_settings = this.show_templates_settings = this.show_other_settings = true
            this.clear()
            var v = this
            introJs().oncomplete(function(){
               v.pop_state()
               v.disable_submit_button = false
               v.text_submit_button = "Submit"
            }).onafterchange(function(e){
               var intro = this
               var n = parseInt(e.getAttribute("data-step"))
               setTimeout(function(){
                  if (n == 1) {
                     v.email = example3.email
                  } else if (n == 2) {
                     v.seq = example3.seq
                  } else if (n == 3) {
                     v.ss = example3.ss
                  } else if (n == 4) {
                     v.constraints = example3.constraints
                  } else if (n == 5) {
                     v.disused_pdbs = example3.disused_pdbs
                  } else if (n == 6) {
                     v.num = example3.num
                     v.num_sampling = example3.num_sampling
                  } else if (n == 7) {
                     v.seed = example3.seed
                  } else if (n == 8) {
                     v.disable_submit_button = true
                     v.text_submit_button = "submitting..."
                  }
               }, 1500)
               setTimeout(function(){intro.nextStep();}, 3000)
            }).setOption("showButtons", false).start()
         },

         show_example1: function(e) { this.set_state(example1) },

         show_example2: function(e) { this.set_state(example2) },

         show_example3: function(e) { this.set_state(example3) },

         check_seq: function() { this.seq = this.seq.replace(/\s+/g, '').toUpperCase() },

         check_ss: function() { this.ss = this.ss.replace(/\s+/g, '') },

         num_changed: function(e) {
            if (isNaN(this.num)) {
               alert(this.num_prompt)
               this.num = 5
            } else {
               if (this.num > 10) this.num = 10
               else if (this.num < 1) this.num = 1
               this.num = parseInt(this.num)
            }
         },

         num_sampling_changed: function(e) {
            if (isNaN(this.num_sampling)) {
               alert(this.num_sampling_prompt)
               this.num_sampling = 1000
            } else {
               if (this.num_sampling > 1000) this.num_sampling = 1000
               else if (this.num_sampling < 1) this.num_sampling = 1
               this.num_sampling = parseInt(this.num_sampling)
            }
         },

         get_running_tasks: function() {
            var v = this
            v.rotate_refresh = 'refresh'
            jian_ajax("GET", "http://biophy.hust.edu.cn/3dRNA/running_tasks/"+v.ip, function(response){
               var r = JSON.parse(response)
               r.sort(function(a, b){
                  return b.submit_time-a.submit_time
               })
               v.running_tasks = r
               v.rotate_refresh = ''
               console.log(r)
            }, function(response){
               //console.log(response)
               v.rotate_refresh = ''
            })
         },

         set_task_type: function(type) { this.task_type = type },

      },

      created: function () {
         var v = this
         v.get_running_tasks()
         window.setInterval(function () {
            v.get_running_tasks()
         }, 5000)
      },

      components: {
         Tasks 
      }
   })

   @if(isset($init_state))
      init_state = {!!$init_state!!}
   v.set_state(init_state)
   @endif

   new Vue({
      el: '#query-task',
      data: {
         query_results: [],
         query_errors: "",
         show_query_errors: false,
      },
      created: function () {
         var v = this
      },
      components: {
         Query,
         Tasks
      },
      methods: {
         submit: function(query, el) {
            var v = this
            jian_ajax("POST", "http://biophy.hust.edu.cn/3dRNA/query", new FormData(el), function(response){
               var r = JSON.parse(response).results
               r.sort(function(a, b){
                  return b.submit_time-a.submit_time
               })
               v.query_results = r
               v.show_query_errors = false
            }, function(response){
               console.log(response)
               v.query_results = []
               v.query_errors = JSON.parse(response).errors
               v.show_query_errors = true
            })
         }
      }
   })

</script>
@endsection

