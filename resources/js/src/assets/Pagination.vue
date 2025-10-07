<template>
    <nav>
        <div class="row align-items-center">
            <div
                class="col-md-6 col-sm-12 col-12 d-flex justify-content-md-start justify-content-sm-center justify-content-center">
                <p class="text-muted">showing {{ meta.from }} to {{ meta.to }}
                    of {{ meta.total }} entries</p>
            </div>
            <div
                class="col-md-6 col-sm-12 col-12 d-flex justify-content-md-end justify-content-sm-center justify-content-center ">
                <ul class="pagination">

                    <div v-for="link in meta.links" :key="link.label">
                        <li class="page-item" :class="{ active: link.active, disabled: link.url == null }">
                            <span
                                @click="link.url != null ? changeUrl({ name: props.routeName, query: getQueryParameters(link.url) }) : null"
                                class="page-link">{{
                                    labelString(link.label) }}</span>
                        </li>
                    </div>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { useRouter } from 'vue-router';
const props = defineProps(['meta', 'routeName'])

const router = useRouter()

const changeUrl = (url) => {
    router.push(url)
}

const labelString = (label) => {
    if (label === "&laquo; Previous")
        return "‹‹"
    if (label === "Next &raquo;")
        return "››"
    return label
}

const getQueryParameters = (url) => {
    const urlObject = new URL(url)
    const queryParams = new URLSearchParams(urlObject.search);
    const paramsObject = {};
    queryParams.forEach((value, key) => {
        paramsObject[key] = value;
    });
    return paramsObject
}

</script>

<style scoped>
.disabled {
    cursor: default !important
}

li:hover {
    cursor: pointer;
}
</style>
