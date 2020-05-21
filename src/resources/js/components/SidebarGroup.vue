<template>
<div class="card">
    <div class="card-header h4" v-text="title"></div>
    <div class="card-body">
        <ul class="list-group" v-for="(group, index) in groups" key="index">
            <a
                class="list-group-item list-group-item-action list-group-item-info"
                v-text="group.name"
                :href="`/groups/`+group.id"
            ></a>
        </ul>
    </div>
</div>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            required: true
        }
    },

    data() {
        return {
            groups: [],
            link: "/api/groups"
        }
    },

    async created() {
        try {
           const {data: groups} = await axios.get(this.link);
           this.groups = groups;
        } catch (ex) {
            console.error(ex.response);
        }
    }
}
</script>
