<script id="jn-switch-t" type="text/x-template">
   <div :class="['jn-switch', {'jn-switch-on':switch_on}]" @click="click">
      <div class="jn-switch-ball"></div>
      <input type="hidden" :name="name" :value="value">
   </div>
</script>

<script>
   Vue.component('jn-switch', {
      template: '#jn-switch-t',
      data: function () {
         return {
            switch_on: false,
         }
      },
      props: ['name', 'value'],
      methods: {
         click: function () {
            this.switch_on ^= true
            this.$emit('input', this.switch_on)
         }
      }
   })
</script>

<style>
   .jn-switch {
      width: 40px;
      height: 22px;
      margin: 6px 0px;
      padding: 3px;
      border-radius: 12px;
      background-color: rgb(192, 204, 218);
      cursor: pointer;
   }

   .jn-switch-ball {
      height: 16px;
      width: 16px;
      border-radius: 8px;
      background-color: #ffffff;
   }

   .jn-switch-on {
      padding-left: 21px;
      padding-right: 3px;
      background-color: rgb(32, 160, 255);
   }

</style>

