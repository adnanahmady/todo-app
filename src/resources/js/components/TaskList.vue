<template>
    <ul class="list-group">
        <li 
            class="list-group-item list-group-item-action"
            :class="isDone(task)"
            :style="taskStyle(task)"
            v-for="task in tasks"
            @click="doneTask(task)"
        >
            <i 
                class="fa fa-check"
                aria-hidden="true" 
                v-if="!! task.finish_date"></i>
            <i 
                class="fa fa-circle-o"
                aria-hidden="true" 
                v-else></i>
            <span class="text-dark" v-text="task.body"></span>
        </li>
    </ul>
</template>

<script>
    export default {
        props: {
            tasks: {
                type: Array,
                require: true
            },

            group: {
                type: Object,
                require: true
            },
        },

        methods: {
            taskStyle(task) {
                return {
                    textDecoration: !! task.finish_date && 'line-through',
                    cursor: ! task.finish_date && 'pointer'
                };
            },
            isDone(task) {
                return (
                    "list-group-item-" +
                    (!! task.finish_date ? 'success' : 'light')
                );
            },

            async doneTask(task) {
                try {
                    const index = this.tasks.indexOf(task);
                    const {data: {data: newTask}} = await axios.put(
                        `/api/groups/${this.group.id}/tasks/${task.id}`
                    );
                    
                    this.$emit('doneTask', {index, newTask});
                }
                catch (ex) 
                {
                    if (ex.response && ex.response.message) {
                        console.log(ex.response.message);
                    } else {
                        console.log(ex.message);
                    }
                }
            },
        }
    }
</script>
