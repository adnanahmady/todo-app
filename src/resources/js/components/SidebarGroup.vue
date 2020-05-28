<template>
<div class="card">
    <div class="card-header h4" v-text="title"></div>
    <div class="card-body">
        <div class="list-group">
            <a
                class="list-group-item list-group-item-action list-group-item-info"
                v-text="group.name"
                :href="`/groups/`+group.id"
                v-for="(group, index) in groups"
            ></a>
        </div>
    </div>
    <div class="card-footer">
            <a
                class="btn btn-primary btn-block"
                href="/groups/create">
                <i class="fa fa-plus" aria-hidden="true"></i>
                <span class="ml-2">New Group</span>
            </a>
        </form>
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
