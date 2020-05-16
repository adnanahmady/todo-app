<template>
<span v-if="isLoading">
    <i class="fa fa-spinner fa-pulse fa-1x text-info fa-fw"></i>
    <span class="sr-only">Loading...</span>
</span>
</template>

<script>
export default {
    props: {
        isLoading: {
            type: Boolean,
            required: true
        }
    }
}
</script>
