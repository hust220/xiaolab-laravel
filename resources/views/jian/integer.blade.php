<script id="jn-integer-t" type="text/x-template">
   <div class="jn-integer">
      <span v-text="value"></span>
      <div class="jn-integer-plus iconfont icon-plus" @click="plus"></div>
      <div class="jn-integer-minus iconfont icon-minus" @click="minus"></div>
      <input type="hidden" :name="name" :value="value">
   </div>
</script>

<script>
   Vue.component('jn-integer', {
      template: '#jn-integer-t',

      props: ['name', 'value', 'min', 'max'],

      methods: {
         plus: function () {
            if (this.value < this.max) {
               this.$emit('input', this.value + 1)
            }
         },

         minus: function () {
            if (this.value > this.min) {
               this.$emit('input', this.value - 1)
            }
         }
      }
   })
</script>

<style>
   .jn-integer {
      height: 34px;
      padding: 6px;
      border: 1px solid rgb(204, 204, 204);
      border-radius: 4px;
      position: relative;
   }

   .jn-integer-plus {
      line-height: 32px;
      height: 32px;
      width: 34px;
      border-left: 1px solid rgb(204, 204, 204);
      position: absolute;
      top: 0px;
      right: 0px;
      text-align: center;
      cursor: pointer;
      color: rgb(153, 169, 191);
   }

   .jn-integer-minus {
      line-height: 32px;
      height: 32px;
      width: 34px;
      border-left: 1px solid rgb(204, 204, 204);
      position: absolute;
      top: 0px;
      right: 34px;
      text-align: center;
      cursor: pointer;
      color: rgb(153, 169, 191);
   }

   .jn-integer:hover {
      border-color: rgb(32, 160, 255);
      box-shadow: 0px 0px 10px rgba(0, 0, 100, 0.2);
      -moz-box-shadow: 0px 0px 10px rgba(0, 0, 100, 0.2);
      -webkit-box-shadow: 0px 0px 10px rgba(0, 0, 100, 0.2);
   }

   .jn-integer-minus:hover, .jn-integer-plus:hover {
      color: rgb(32, 160, 255);
   }

</style>
