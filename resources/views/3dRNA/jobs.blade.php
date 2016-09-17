@extends('layouts.rna')

@section('head') @parent
    <link rel="stylesheet" type="text/css" href="/css/sortable.css" media="all" />
    <link rel="stylesheet" type="text/css" href="/css/3dRNA/jobs.css" media="all" />
    <script src="/js/sortable.js"></script>
    <script src="/js/pageable.js"></script>
    <script src="/js/3dRNA/jobs.js"></script>
@endsection

@section('content-main')
    <div class='panel panel-default'>
        <div class='panel-body'>
            @if(!isset($items))
                <p>No match!</p>
            @else
                <div class="table-responsive">
                    <table class='jobs sortable pageable table table-hover table-bordered'>
                        <thead>
                            <tr>
                                @foreach ($items as $item)
                                    <th class='sort-button @if($names[$item]=='Job ID'||$names[$item]=='Number') sort-num @endif '>
                                        {{$names[$item]}}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($result as $array)
                                <tr>
                                    @foreach($items as $item)
                                        @if ($item == "submit_time" or $item == "done_time")
                                            <td type='time'>{{$array[$item]}}</td>
                                        @elseif ($item == "num")
                                            <td><a href='{{"/3dRNA/result/$array[$item]"}}'>{{$array[$item]}}</a></td>
                                        @else
                                            <td>{{$array[$item]}}</td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

