<template>
<div class="row">
    <div class="col">
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
        
        <div class="list-group">
            <div 
                class="list-group-item list-group-item-action list-group-item-success"
                v-for="task in tasks"
                v-text="task"
            ></div>
        </div>
    </div>
</div>
</template>

<script>
    import Loader from './Loader.vue';

    export default {
        props: ['group'],
        components: { Loader },
        data() {
            return {
                tasks: [],
                isLoading: false,
                isTyping: undefined,
                typingTimer: undefined,
                body: ''
            };
        },

        created() {
            axios
                .get('/api/groups/'+this.group.id)
                .then(response => (this.tasks = response.data));
            
                this.channel.listen('NewTaskDidCreateEvent', ({user, task}) => {
                    this.isTyping = undefined;
                    this.tasks.push(task.body);
                })
                .listenForWhisper('typing', (e) => {
                    this.isTyping = e;

                    if (this.typingTimer) clearTimeout(this.typingTimer);

                    this.typingTimer = setTimeout(() => this.isTyping = undefined, 3000);
                });
        },

        computed: {
            isBodyEmpty() {
                const body = this.body.trim();

                return (body.length == 0) ? true : false;
            },

            typeNote() {
                return this.isTyping.name + ' is typing...';
            },
            
            channel() {
                return window.Echo.private('groups.'+this.group.id);
            }
        },
        
        methods: {
            itIsTyping() {
                    this.channel.whisper('typing', {name: window.App.user.name});
            },
            newTask() {
                if (this.isBodyEmpty) return;
                this.isLoading = true;
                this.isTyping = undefined;
                axios.post('/api/groups/'+this.group.id+'/tasks', { body: this.body })
                    .then(() => {
                        this.tasks.push(this.body);
                        this.body = '';
                        this.isLoading = false;
                    })
                    .catch((err) => { this.isLoading = false });
            }
        }
    }
</script>
