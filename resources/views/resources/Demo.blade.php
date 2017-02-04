<script id="demo-t" type="text/x-template">
   <div id="header-top" class='jumbotro hidden-xs' v-text="text"></div>
</script>

<script>
   var Demo = {
      template: '#demo-t',
      props: [ 'text' ],
   }
</script>

<style>
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
</style>
