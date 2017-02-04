Vue.component('vue-close', {
    template: '\
        <button type="button" class="close" @click="click"> \
            <span>&times;</span> \
        </button> \
    ',
    props: ['click'],
})

Vue.component('vue-query', {
    template: ' \
        <form v-el:form class="form-horizontal" onsubmit="return false"> \
            <div class="form-group" style="margin-bottom:0px"> \
                <div class="col-sm-offset-1 col-sm-6 col-xs-8"> \
                    <input type="text" class="form-control" name="query" v-model="query" placeholder="Task ID / Email / IP"> \
                </div> \
                <div class="col-sm-2 col-xs-2"> \
                    <button @click="click" class="btn btn-info">Query</button> \
                </div> \
            </div> \
        </form> \
    ',
    data: function(){
        return {
            query: "",
        }
    },
    methods: {
        click: function(){
            if (this.query.trim()) {
                this.$dispatch('vue-query-submit', this.query, this.$els.form);
            }
        }
    },
})

var VueAlert = ' \
    <div class="alert alert-{{type}}" v-if="show"> \
        <button type="button" class="close" @click="show = false"> \
            <span>&times;</span> \
        </button> \
        <ul> \
            <li v-for="el in contents"> \
                {{{el}}} \
            </li> \
        </ul> \
    </div> \
'

Vue.component('vue-alert', {
    template: VueAlert,
    props: ['type', 'show', 'contents'],
})

var VueProgress = ' \
<div class="progress"> \
    <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" :aria-valuenow="percentage" aria-valuemin="0" aria-valuemax="100" style="width: {{percentage}}%"> \
        <span class="sr-only">{{percentage}}% Complete</span> \
    </div> \
</div> \
'

Vue.component('vue-progress', {
    template: VueProgress,
    props: ['percentage'],
})

Vue.component('vue-tasks', {
    template: '\
        <table class="table"> \
            <thead> \
                <tr> \
                    <th>Task ID</th> \
                    <th>State</th> \
                    <th>Email</th> \
                    <th>IP</th> \
                    <th>Submitted At</th> \
                </tr> \
            </thead> \
            <tbody> \
                <tr v-for="task in tasks.slice((page-1)*page_size,page*page_size)"> \
                    <td><a target="_blank" href="/{{task_type}}/{{task[items.id]}}">{{task[items.id]}}</a></td> \
                    <td>{{task.state}}</td> \
                    <td>{{task.email}}</td> \
                    <td>{{task.ip}}</td> \
                    <td>{{local_time(task[items.submit_at])}}</td> \
                </tr> \
            </tbody> \
        </table> \
        <nav class="text-center"> \
            <ul class="pagination"> \
                <li><a @click="prev_page">&laquo;</a></li> \
                <li v-for="i in pages" :class="{\'active\':i==page}"><a @click="page=i">{{i}}</a></li> \
                <li><a @click="next_page">&raquo;</a></li> \
            </ul> \
        </nav>',
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
})
 
