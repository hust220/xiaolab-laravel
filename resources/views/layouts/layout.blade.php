<!DOCTYPE HTML>
<html>
   <head>
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

      <link rel="stylesheet" type="text/css" href="/jn-fonts/iconfont.css" media="all" />

      <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css" media="all" />
      <script src="/js/jquery.min.js"></script>
      <script src="/js/bootstrap.min.js"></script>

      <link href="/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
      <script src="/js/fileinput.min.js"></script>

      <!-- element UI -->
      <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-default/index.css">

      <meta charset="utf-8">
      @yield('head')
      <style>
         body { 
               background-color: #f4f4f4; 
               padding-top: 50px;
         }

         #nav.navbar {
               background-color: #358ab9;
         }

         #nav .navbar-text {
               color: #ffffff;
         }

         #nav.navbar a {
               color: #ffffff;
         }

         #nav ul.dropdown-menu a {
               color: #000000;
         }

         #nav li.open a {
               background-color: #ffffff;
               color: #000000;
         }

         a {
               color: rgb(107, 152, 191);
               text-decoration: none;
               cursor: pointer;
         }

         #header-top { 
               height: 200px; 
               width: 100%; 
               margin: 0px; 
               border: 1px;
               padding: 120px 0px 0px 160px; 
               text-align: left; 
               background: url('/images/bg.jpg') no-repeat; 
         }

         #header-top a { 
               font-weight: bold;
               font-size: 30px; 
               color: rgb(255, 255, 255); 
               text-decoration: none;
         }

         .box {
               border: 1px solid #bebebe;
               background: white;
               margin-top: 7px;
               margin-bottom: 7px;
               padding: 12px;
         }

         .smallbox {
               margin: 7px;
               padding: 7px;
               border: 7px solid rgb(156, 192, 222);
               background: white;
               border-radius: 15px;
               font-size: 90%;
               text-align: left;
         }

         .greybox {
               margin: 7px;
               padding: 15px;
               border: 7px solid rgb(156, 192, 222);
               background: rgb(107, 152, 191);
               border-radius: 15px;
               color: white;
               font-size: 90%;
               text-align: left;
         }

         h1 {
               margin: 30px 0px;
               font-weight: bold;
         }

         .jn-title {
               margin: 20px 0px;
               padding: 5px;
               background-color: rgb(107, 152, 191);
               color: white;
         }
      </style>
      @yield('style')
      <title>@yield('title')</title>
   </head>

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

               <!-- Services -->
               <li class="dropdown">
               <a href="#" class="dropdown-toggle" data-toggle="dropdown">Services<span class='caret'></span></a>
               <ul class='dropdown-menu'>
                  <li><a href="/3dRNA">3dRNA</a></li>
                  <li><a href="/3dRNA_DG">3dRNA_DG</a></li>
                  <li><a href="/3dDNA">3dDNA</a></li>
                  <li><a href="/3dRNAscore">3dRNAscore</a></li>
                  <li><a href="/DCA">DCA</a></li>
                  <li><a href="/3dRPC">3dRPC</a></li>
               </ul>
               </li>

               <!-- Download -->
               <li class="dropdown">
               <a href="#" class="dropdown-toggle" data-toggle="dropdown">Download<span class='caret'></span></a>
               <ul class='dropdown-menu'>
                  <li><a href="/3dRNAscore.html">3dRNAscore</a></li>
                  <li><a href="/3dRPC.html">3dRPC</a></li>
                  <li><a href="/ASPDOCK.html">ASPDOCK</a></li>
                  <li><a href="/resources/3drna_opt_dca">3dRNA Optimization utilizing DCA</a></li>
               </ul>
               </li>

               <!-- More -->
               <li class="dropdown">
               <a href="#" class="dropdown-toggle" data-toggle="dropdown">More<span class='caret'></span></a>
               <ul class='dropdown-menu'>
                  <li><a href="/links">External links</a></li>
                  <li><a>Documentation</a>
                  <ul >
                     <li><a href="/download/Users' Manual.pdf">3dRNA</a></li>
                     <li><a href="/download/3dRPC-um.pdf">3dRPC</a></li>
                  </ul>
                  <li>
               </ul>
               </li>
               </li>
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
         <script type="text/javascript">
            handle_ie();
            $('li.dropdown').mouseenter(function(){
                  $(this).css('background-color', '#FFF');
                  $(this).children().first().css('color', '#000');
                  $(this).children().last().show();
            });
            $('li.dropdown').mouseleave(function(){
                  $(this).css('background-color', '#358ab9');
                  $(this).children().first().css('color', '#FFF');
                  $(this).children().last().hide();
            });
         </script>
         @yield('main-js')
         <script type="text/javascript">cnzz();</script>
      </div>
   </div>

</body>
</html>

