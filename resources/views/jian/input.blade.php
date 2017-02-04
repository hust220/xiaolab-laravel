<script id="jn-input-t" type="text/x-template">
   <input class="jn-input" :value="value" @input="updateValue($event.target.value)"></input>
</script>

<script>
   Vue.component('jn-input', {
      template: '#jn-input-t',
      props: ['value'],
      methods: {
         updateValue: function (value) {
            this.$emit('input', Number(value))
         }
      }
   })
</script>

<style>
   .jn-input {
      height: 34px;
      padding: 6px;
      border: 1px solid rgb(204, 204, 204);
      border-radius: 4px;
   }

   .jn-input:hover {
      border-color: rgb(32, 160, 255);
      box-shadow: 0px 0px 10px rgba(0, 0, 100, 0.2);
      -moz-box-shadow: 0px 0px 10px rgba(0, 0, 100, 0.2);
      -webkit-box-shadow: 0px 0px 10px rgba(0, 0, 100, 0.2);
   }

</style>

