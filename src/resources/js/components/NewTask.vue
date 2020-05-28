<template>
    <div class="form-group">
        <input 
            class="form-control" 
            v-model="body" 
            @keydown.enter = "newTask" 
            @keydown = "itIsTyping"
            type="text">
    </div>
    <div class="form-group">
        <span
            class="text-info"
            v-if="isTyping"
            v-text="typeNote"
        ></span>
    </div>
    <div class="form-group d-flex">
        <button 
            @click="newTask" 
            class="btn position-relative w-50 mx-auto btn-primary"
        >
            <loader
                class="position-absolute ml-2"
                style="left: 0px" 
                :isLoading="isLoading"
            ></loader>
            <span class="mx-auto">
                <i class="fa fa-pencil" aria-hidden="true"></i>
                New Task
            </span>
        </button>
    </div>
</template>

<script>
    import Loader from './Loader.vue';

    export default {
        props: {
            group: {
                type: Object,
                require: true,
            },

            isLoading: {
                type: Boolean,
                default: false
            },

            isTyping: {
                type: Object,
                require: true
            }
        },
        components: { Loader },
        data() {
            return {
                body: '',
            };
        },

        created() {
            axios
                .get('/api/groups/'+this.group.id)
                .then(response => (this.tasks = response.data));
        },

        computed: {
            isBodyEmpty() {
                const body = this.body.trim();

                return (body.length == 0) ? true : false;
            },

            typeNote() {
                return this.isTyping.name + ' is typing...';
            },
        },
        
        methods: {
            doneTask({index, newTask}) {
                this.tasks.splice(index, 1, newTask);
            },

            itIsTyping() {
                this.channel.whisper('typing', {name: window.App.user.name});
            },

            async newTask() {
                if (this.isBodyEmpty) return;
                this.isLoading = true;
                this.isTyping = undefined;
                try {
                    const {data: {data: task}} = await axios.post(
                        '/api/groups/'+this.group.id+'/tasks',
                        { body: this.body }
                    );
                    this.tasks.push(task);
                    this.body = '';
                    this.isLoading = false;
                } catch (ex) {
                    this.isLoading = false;
                }
            }
        }
    }
</script>
