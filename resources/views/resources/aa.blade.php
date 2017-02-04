<!DOCTYPE html>
<html>
   <head>
      <meta charset="UTF-8">
      <!-- jquery -->
      <script src="/js/jquery.min.js"></script>

      <!-- bootstrap -->
      <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css" media="all" />
      <script src="/js/bootstrap.min.js"></script>

      <!-- element-ui -->
      <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-default/index.css">
   </head>
   <body>
      <div id="app"></div>
      @include('utils.vue')
      @include('utils.element-ui')
      @include('resources.App')
      <script>
         new Vue({
            el: '#app',
            template: '<App />',
            components: {
               App
            },
         })
      </script>
   </body>
</html>
