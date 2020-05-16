<template>
<div class="row">
    <div class="col">
        <div class="form-group">
            <input class="form-control" v-model="body" @keydown.enter="newTask" type="text">
        </div>
        <div class="form-group d-flex">
            <button @click="newTask" class="btn position-relative w-50 mx-auto btn-primary">
                <span class="position-absolute ml-2" style="left: 0px" v-if="isLoading">
                    <i class="fa fa-spinner fa-pulse fa-1x text-info fa-fw"></i>
                    <span class="sr-only">Loading...</span>
                </span>
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
    export default {
        props: ['group'],
        data() {
            return {
                tasks: [],
                isLoading: false,
                body: null
            };
        },

        created() {
            axios
                .get('/api/groups/'+this.group.id)
                .then(response => (this.tasks = response.data));
            
            window.Echo.private('groups.'+this.group.id)
                .listen('NewTaskDidCreateEvent', ({user, task}) => {
                    this.tasks.push(task.body);
                });
        },
        
        methods: {
            newTask() {
                this.isLoading = true;
                axios.post('/api/groups/'+this.group.id+'/tasks', { body: this.body })
                    .then(() => {
                        this.tasks.push(this.body);
                        this.body = null;
                        this.isLoading = false;
                    });
            }
        }
    }
</script>
