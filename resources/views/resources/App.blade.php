<script id="app-t" type="text/x-template">
   <div>
      <top-nav></top-nav>
      <demo :text="'hi'"><demo>
   </div>
</script>

@include('resources.TopNav')
@include('resources.Demo')

<script>
   var App = {
      template: '#app-t',
      data: function () {
         return {
            visible: false
         }
      },
      components: {
         TopNav, Demo
      }
   }
</script>
