<template>
<div class="row">
    <div class="col col-md-8">
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
            </div>
        </div>

        <div class="row">
            <div class="col">
                <task-list :tasks="tasks" :group="group" @doneTask="doneTask"></task-list>
            </div>
        </div>
    </div>
    <div class="col col-md-4">
        <div class="card">
            <div class="card-header">
                <div class="h4 card-title">Online Members</div>
            </div>
            <div class="card-body">
                <ul>
                    <li 
                        class="text-success"
                        v-for="member in members"
                        v-text="member.name"></li>
                </ul>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    import Loader from './Loader.vue';
    import TaskList from './TaskList.vue';

    export default {
        props: ['group'],
        components: { Loader, TaskList },
        data() {
            return {
                tasks: [],
                isLoading: false,
                isTyping: undefined,
                typingTimer: undefined,
                body: '',
                members: []
            };
        },

        created() {
            axios
                .get('/api/groups/'+this.group.id)
                .then(response => (this.tasks = response.data));
            
            this.channel
                .here(users => {
                    this.members = users;
                })
                .joining(user => {
                    this.members.push(user);
                })
                .leaving(user => {
                    this.members.splice(this.members.indexOf(user));
                })
                .listen('TaskIsDone', ({task}) => {
                    const oldTask = [
                        ...this.tasks.filter((t) => t.id == task.id)
                    ].shift();
                    const index = this.tasks.indexOf(oldTask);
                    if (index > -1) {
                        this.tasks.splice(index, 1, task);
                        this.tasks.sort(
                            ({finish_date}) => finish_date ? 1 : -1
                        );
                    }
                })
                .listen('NewTaskDidCreateEvent', ({user, task}) => {
                    this.isTyping = undefined;
                    this.tasks.push(task);
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
                return window.Echo.join('groups.'+this.group.id);
            }
        },
        
        methods: {
            isDone(task) {
                return (
                    "list-group-item-" +
                    (!! task.finish_date ? 'success' : 'light')
                );
            },

            doneTask({index, newTask}) {
                this.tasks.splice(index, 1, newTask);
                this.tasks.sort(
                    ({finish_date}) => finish_date ? 1 : -1
                );
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
