<script id="query-t" type="text/x-template">
   <form ref='queryform' @submit.prevent="submit">
      <div class="form-group" style="margin-bottom:0px"> 
         <div class="col-sm-offset-1 col-sm-6 col-xs-8"> 
            <input name="query" class="form-control" v-model="query" placeholder="Task ID / Email / IP"></input>
         </div> 
         <div class="col-sm-2 col-xs-2"> 
            <button class="btn btn-info" type="info" @click="submit">Query</button>
         </div> 
      </div> 
   </form>
</script>

<script>
   var Query = {
      template: '#query-t',
      data: function() {
         return {
            query: '',
         }
      },
      methods: {
         submit: function(){
            console.log('emit')
            if (this.query.trim()) {
               console.log('emit')
               this.$emit('submitquery', this.query, this.$refs.queryform)
            }
            return false
         }
      },
   }
</script>

