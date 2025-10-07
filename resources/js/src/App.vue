<template>
    <!-- <Preloader /> -->
    <Navbar v-if="authenticated" />
    <Sidebar v-if="authenticated" />

    <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in" >
            <component :is="Component" />
        </transition>
    </router-view>

    <Footer v-if="authenticated" />
    <div class="teleport-modal"></div>
    <vue3-progress-bar></vue3-progress-bar>
    <!-- <ControlSidebar/> -->
</template>

<script setup>

//import Preloader from "@/views/layouts/Preloader.vue";
import Navbar from "../src/views/layouts/Navbar.vue";
import Sidebar from "../src/views/layouts/Sidebar.vue";
import Footer from "../src/views/layouts/Footer.vue";
import {computed, onMounted, ref, watch} from "vue";

const isOnline = ref(true);
import { useStore } from 'vuex'
const store = useStore()
const authenticated = computed(() => store.getters["auth/getAuthenticated"])

const checkOnlineStatus = () => isOnline.value = navigator.onLine

watch(isOnline, (newVal, oldValue)=>{
    if(isOnline.value === false || undefined) alert("No Internet Connection");
})

onMounted(()=>{
    checkOnlineStatus();
    window.addEventListener('online', checkOnlineStatus);
    window.addEventListener('offline', checkOnlineStatus);
})
</script>

<style>
.red-star {
    color: red;
}

.page {
    position: absolute;
}

.fade-ender-from,
.fade-leave-to {
    opacity: 0;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease-out;
}

.vue3-progress-bar {
    width: 100%;
    height: 3px !important;
    transform: translate3d(-100%, 0, 0);
    background-color: red !important;
    transition: all .2s ease;
}
</style>
