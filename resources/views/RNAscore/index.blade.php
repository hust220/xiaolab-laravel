@extends('layouts.rna')

@section('title', '3dRNAscore')

@section('header-top')
    <a id="title" href="/3dRNA">3dRNAscore: Evaluation of RNA 3D structures</a>
@endsection

@section('head') @parent
@endsection

@section('content-main')
    <div class='panel panel-default'>
        <div class='panel-body'>
            <form v-el:form class='form-horizontal' name='form1' enctype="multipart/form-data" onsubmit="return false;" >
                <!-- <input type="hidden" name="_token" v-model="token"> -->

                <div class='form-group'>
                    <label class='col-sm-3 control-label'>Input the RNA 3D structure:</label>
                    <div class='col-sm-3'>
                        <select name="input_type" class='form-control' v-model="input_type">
                            <option value='single' selected>Single structure</option>
                            <option value='multiple'>Multiple structures</option>
                        </select>
                    </div>
                </div>

                <div class='form-group'>
                    <div class='col-sm-offset-3 col-sm-3'>
                        <input v-el:file type="file" name="struct_file" />
                    </div>
                    <div class='col-sm-3'>Supported file suffixes: <span id='suffix'>@{{extensions}}</span></div>
                </div>

                <!--
                <div class='form-group'>
                    <label class='col-sm-3 control-label'>Weight (optional): </label>
                    <div class='col-sm-2'>
                        <input type='text' name='weight' class='form-control' v-model="weight" >
                    </div>
                </div>

                <div class='form-group'>
                    <label class='col-sm-3 control-label'>Parameter file (optional): </label>
                    <div class='col-sm-2'>
                        <input type='file' name='par_file'>
                    </div>
                </div>

                -->
                <div class='form-group'>
                    <div class='col-sm-offset-3 col-sm-3'>
                        <button class='btn btn-info' @click=submit :disabled="submit_disabled">@{{button_text}}</button>
                        <button type='reset' class='btn btn-warning'>Clear</button>
                    </div>
                </div>

            </form>

            <div class="alert alert-danger" v-if="show_errors">
                <button type="button" class="close" @click="show_errors ^= true">
                    <span>&times;</span>
                </button>
                <ul>
                    <li v-for="error in errors">
                        @{{error}}
                    </li>
                </ul>
            </div>

            <div class="alert alert-success" v-if="show_results">
                <button type="button" class="close" @click="show_results ^= true">
                    <span>&times;</span>
                </button>
                <ul>
                    <li v-for="result in results">
                        @{{result}}
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <!-- picture -->
    <div class='panel panel-default'>
        <div class='panel-body'>
            <div class='row'>
                <div class='col-sm-6'>
                    <div class="thumbnail">
                        <img class='img-responsive' src="/image/3dRNAscore/fig1.png" />
                    </div>
                    <p>(A) Energy distribution of the distance between N9 of adenine
                    and N1 of uracil. (B) Diagram of the three representative distance between
                    N9 of adenine and N1 of uracil.</p>
                </div>
                <div class='col-sm-6'>
                    <div class="thumbnail">
                        <img class='img-responsive' src="/image/3dRNAscore/fig2.png" />
                    </div>
                    <p>(A) Sequence, secondary structure and 3D structure of 1AFX.
                    (B) Base-stacking energies between adjacent two nucleotides in 1AFX calculated by 3dRNAscore. '1-2' means base-stacking energy between the
                    first and the second nucleotide, '2-3' means the second to the third ...
                    and so on. The lower the energy, the better the base-stacking. (C) Basepairing energies between each possible base-pair in 1AFX calculated by
                    3dRNAscore. The lower the energy, the better the base-pairing.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- References -->
    <div class='panel panel-default'>
        <div class='panel-body'>
            References:
            <br />
            <ol>
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
    <script type="text/javascript" src="/js/vue.min.js"></script>
    <script type="text/javascript" src="/js/vue-resource.min.js"></script>
    <script type="text/javascript">
        var browser = {
            versions: function() {
                var u = navigator.userAgent, app = navigator.appVersion;
                return {//移动终端浏览器版本信息
                    trident: u.indexOf('Trident') > -1, //IE内核
                    presto: u.indexOf('Presto') > -1, //opera内核
                    webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
                    gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
                    mobile: !!u.match(/AppleWebKit.*Mobile.*/) || !!u.match(/AppleWebKit/), //是否为移动终端
                    ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
                    android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或者uc浏览器
                    iPhone: u.indexOf('iPhone') > -1 || u.indexOf('Mac') > -1, //是否为iPhone或者QQHD浏览器
                    iPad: u.indexOf('iPad') > -1, //是否iPad
                    webApp: u.indexOf('Safari') == -1 //是否web应该程序，没有头部与底部
                };
            }(),
            language: (navigator.browserLanguage || navigator.language).toLowerCase()
        }

        Vue.http.options.emulateJSON = true;
        var vue = new Vue({
            el: "#vue-main",

            data: {
                input_type: "single",
                weight: "",
                errors: "",
                show_errors: false,
                show_results: false,
                results: '',
                submit_disabled: false,
            },

            computed: {
                extensions: function() {
                    if (this.input_type == "single") return ".pdb";
                    else return ".tar.gz";
                },

                button_text: function() {
                    if (this.submit_disabled) return "Submitting...";
                    else return "Submit";
                },
            },

            methods: {
                submit: function(){
                    var v = this;

                    v.show_errors = false;
                    v.show_results = false;
                    v.submit_disabled = true;

                    var xhr = new XMLHttpRequest();
                    xhr.open( 'POST', 'http://biophy.hust.edu.cn/3dRNAscore/submit', true );
                    xhr.setRequestHeader('X-Requested-With','XMLHttpRequest');
                    xhr.onreadystatechange=function() {
                        if (xhr.readyState==4) {
                            console.log(xhr.responseText);
                            var r = JSON.parse(xhr.responseText);
                            if ('results' in r) {
                                v.results = r.results;
                                v.show_errors = false;
                                v.show_results = true;
                            } else {
                                v.errors = r;
                                v.show_errors = true;
                                v.show_results = false;
                            }
                            v.submit_disabled = false;
                        }
                    }
                    xhr.send(new FormData(v.$els.form));
                },
            },
        });

    </script>

@endsection

