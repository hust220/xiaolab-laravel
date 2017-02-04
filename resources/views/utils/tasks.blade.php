<script id="tasks-t" type="text/x-template">
   <div>
      <table class="table"> 
         <thead> 
            <tr> 
               <th>Task ID</th> 
               <th>State</th> 
               <th>Email</th> 
               <th>IP</th> 
               <th>Submitted At</th> 
            </tr> 
         </thead> 
         <tbody> 
            <tr v-for="task in tasks.slice((page-1)*page_size,page*page_size)"> 
               <td><a target="_blank" :href="'/'+task_type+'/'+task[items.id]" v-text="task[items.id]"></a></td> 
               <td v-text="task.state"></td> 
               <td v-text="task.email"></td> 
               <td v-text="task.ip"></td> 
               <td v-text="local_time(task[items.submit_at])"></td> 
            </tr> 
         </tbody> 
      </table> 
      <nav class="text-center"> 
         <ul class="pagination"> 
            <li><a @click="prev_page">&laquo;</a></li> 
            <li v-for="i in pages" :class="{'active':i==page}"><a @click="page=i" v-text="i"></a></li> 
            <li><a @click="next_page">&raquo;</a></li> 
         </ul> 
      </nav>',
   </div>
</script>

<script>
   var Tasks = {
      template: '#tasks-t',
      props: ['tasks', 'page', 'page_size', 'items', 'task_type'],
      methods: {
         next_page() {
            if (this.page < this.pages.length) this.page += 1
         },

         prev_page() {
            if (this.page > 1) this.page -= 1
         },

         local_time(nS) {     
            return new Date(parseInt(nS) * 1000).toLocaleString().replace(/:\d{1,2}$/,' ');     
         }
      },
      computed: {
         pages() {
            l = []
            for (i=1; (i-1)*this.page_size+1<=this.tasks.length; i++) {
               l.push(i)
            }
            return l
         }
      },
      watch: {
         'tasks': function(val, oldVal) {
            this.page = 1
         },
      },
   }
</script>

