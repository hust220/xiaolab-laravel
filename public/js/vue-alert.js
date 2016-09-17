var VueAlert = ' \
    <div class="alert alert-{{type}}" v-if="show"> \
        <button type="button" class="close" @click="show = false"> \
            <span>&times;</span> \
        </button> \
        <ul> \
            <li v-for="el in contents"> \
                {{el}} \
            </li> \
        </ul> \
    </div> \
';

Vue.component('vue-alert', {
    template: VueAlert,
    props: ['type', 'show', 'contents'],
});

