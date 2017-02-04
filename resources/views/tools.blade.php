@extends('layouts.rna')

@section('header-top')
    <a id="title" href="/links">Tools</a>
@endsection

@section('content-main')
@endsection

@section('main-js')
    <script type="text/javascript" src="/js/vue.min.js"></script>
    <script type="text/javascript">
        new Vue({
            el: "#vue-main",
            data: {},
        })
    </script>
@endsection

