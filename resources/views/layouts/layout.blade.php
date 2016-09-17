<!DOCTYPE HTML>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css" media="all" />
    <!-- <link rel="stylesheet" type="text/css" href="/css/bootstrap-switch.min.css" media="all" /> -->
    <link rel="stylesheet" type="text/css" href="/css/layouts/layout.css" media="all" />

    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <!-- <script src="/js/bootstrap-switch.min.js"></script> -->
    <script src="/js/layouts/layout.js"></script>
    <meta charset="utf-8">
    @yield('head')
    <title>@yield('title')</title></head>
  
    <body>
        <nav id='nav' class='navbar navbar-default navbar-fixed-top'>
            <div class='container'>
                <div class='navbar-header'>
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href='/' class='navbar-brand'>XiaoLab</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class='nav navbar-nav'>
                        <li class='visible-lg'><a href="/home.html">Home</a></li>
                        <li class='visible-md visible-lg'><a href="/people.html">People</a></li>
                        <li><a href="/publications">Publications</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Services<span class='caret'></span></a>
                            <ul class='dropdown-menu'>
                                <li><a href="/3dRNA">3dRNA</a></li>
                                <li><a href="/3dRNA_DG">3dRNA_DG</a></li>
                                <li><a href="/3dDNA">3dDNA</a></li>
                                <li><a href="/3dRNAscore">3dRNAscore</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Download<span class='caret'></span></a>
                            <ul class='dropdown-menu'>
                                <li><a href="/3dRNAscore.html">3dRNAscore</a></li>
                                <li><a href="/3dRPC.html">3dRPC</a></li>
                                <li><a href="/ASPDOCK.html">ASPDOCK</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Links<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-header">RNA 2D Prediction Methods</li>
                                <li><a href="http://mfold.rna.albany.edu/?q=mfold" target="_blank">Mfold</a></li>
                                <li><a href="http://bibiserv.techfak.uni-bielefeld.de/pkiss" target="_blank">pKiss</a></li>
                                <li class="dropdown-header">RNA Databases</li>
                                <li><a href="http://www.rcsb.org/pdb/home/home.do" target="_blank">PDB</a></li>
                                <li><a href="http://ndbserver.rutgers.edu/" target="_blank">NDB</a></li>
                                <li><a href="http://rfam.xfam.org/" target="_blank">Rfam</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">More<span class='caret'></span></a>
                            <ul class='dropdown-menu'>
                                <li class="dropdown-header">Documentation</li>
                                <li><a href="/download/Users' Manual.pdf">3dRNA</a></li>
                                <li class="divider"></li>
                                <li><a href="mailto:wj_hust08@hust.edu.cn?cc=yxiao@hust.edu.cn">Contact us</a></li>
                            </ul>
                        </li>
                    </ul>

                    <p class='navbar-text navbar-right' style="margin-right:0px">
                        <b>{{$num_users}}</b> users. <b>{{$num_diff_visitors}}</b> visitors. <!--<b>{{$num_visitors}}</b> visits.-->
                    </p>
                </div>
            </div>
        </nav>

        <div class='container' id='vue-main'>
            <div id="header-top" class='jumbotro hidden-xs'>
                @yield('header-top')
            </div>

            <div id='content' class='row' style="margin-top:20px">
                <div id="content-main" class='col-lg-12'>
                    @yield('content-main')
                </div>
            </div>

            <div id="footer" class='row text-center'>
                <script type="text/javascript" src="/js/utils.js"></script>
                <script type="text/javascript">handle_ie();</script>
                @yield('main-js')
                <script type="text/javascript">cnzz();</script>
            </div>
        </div>

    </body>
</html>

