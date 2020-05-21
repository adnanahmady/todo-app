<template>
    <div class="card badge-light">
        <div class="card-header">
            <strong
                v-text="title"
                >
            </strong>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="required" for="name">Name</label>
                <input
                    id="name"
                    class="form-control"
                    v-model="name"
                    type="text"
                    autofocus="on"
                    @keydown.enter="createGroup"
                    >
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-4 mr-auto">
                    <a class="btn btn-danger btn-block" href="/home">
                        Cancel
                    </a>
                </div>
                <div class="col-md-4 ml-auto">
                    <button class="btn btn-primary btn-block" @click="createGroup">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        Create
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            require: true
        }
    },

    data() {
        return {
            name: '',
            link: "/api/groups"
        }
    },

    methods: {
        async createGroup() {
            if (! this.name) return ;
            try {
                await axios.post(this.link, {name: this.name});

                window.location = '/home';
            } catch (ex) {
                console.log(ex.message);
            }
        }
    }
}
</script>
