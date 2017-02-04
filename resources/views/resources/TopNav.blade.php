<script id="top-nav-t" type="text/x-template">
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

      </p>
   </div>
</div>
</nav>

</script>

<script>
   var TopNav = {
      template: '#top-nav-t',
   }
</script>

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

   h1 {
      margin: 30px 0px;
      font-weight: bold;
   }
</style>
