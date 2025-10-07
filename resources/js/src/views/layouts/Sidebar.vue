<template>
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <router-link :to="{ name: 'dashboard' }" class="brand-link">
            <img :src="'/dist/img/pos-logo.png'" alt="Fusion Logo" class="brand-image img-circle elevation-3"
                 style="opacity: .8">
            <span class="brand-text font-weight-light">Fusion Café</span>
        </router-link>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img
                        :src="`https://ui-avatars.com/api/?name=${userName.split(' ')[0]}+${userName.split(' ')[1]}&background=0D8ABC&color=fff`"
                        class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ userName }}</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <li class="nav-item" v-for="navigation in navigations" :key="navigation.link">
                        <NavbarLinks :new-tab="navigation.newTab" :children="navigation.children"
                                     :icon="navigation.icon"
                                     :link="navigation.link" :name="navigation.name"/>
                    </li>
                    <li @click="handelLogoutClick" class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Log out</p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
</template>

<script setup>
import NavbarLinks from "../layouts/components/NavbarLinks.vue"
import getLogout from '../../composables/auth/getLogout'
import {computed, onBeforeMount} from "vue";
import {useStore} from 'vuex'

const store = useStore()

const {logout} = getLogout()

const userName = computed(() => store.getters["auth/getName"])
const userRole = computed(() => store.getters["auth/getRole"])

const handelLogoutClick = () => {
    logout().then(() => {
            window.location.href = '/login'
        }
    ).catch()
}

const navigations = [
    {
        isHeader: false,
        newTab: false,
        name: 'Units',
        link: 'units',
        icon: 'fas fa-balance-scale',
        children: null
    },
    {
        isHeader: false,
        newTab: false,
        name: 'Categories',
        link: 'categories',
        icon: 'fas fa-database',
        children: null},
    {
        isHeader: false,
        newTab: false,
        name: 'Products',
        link: 'products',
        icon: 'fas fa-shopping-basket',
        children: null
    },
    {
        isHeader: false,
        newTab: false,
        name: 'Product Variants',
        link: 'productVariants',
        icon: 'fas fa-utensils',
        children: null
    },
    {
        isHeader: false,
        newTab: false,
        name: 'Tables',
        link: 'tables',
        icon: 'fas fa-umbrella-beach',
        children: null
    },
    {
        isHeader: false,
        newTab: false,
        name: 'Orders',
        link: 'orders',
        icon: 'fas fa-chalkboard-teacher',
        children: null
    },
    {
        isHeader: false,
        newTab: false,
        name: 'Sales',
        link: 'sales',
        icon: 'fas fa-shopping-cart',
        children: null
    },
    {
        isHeader: false,
        newTab: false,
        name: 'Purchases',
        link: 'purchases',
        icon: 'fas fa-money-check-alt',
        children: null
    },
    //{ isHeader: false, newTab: false, name: 'Product Stock', link: 'product-stocks', icon: 'fas fa-circle', children: null },

    // {
    //     isHeader: false, newTab: false, name: 'Dashboard', link: 'dashboard', icon: 'fas fa-file', children: [
    //         { isHeader: false, newTab: false, name: 'TestPage', link: 'testpage', icon: 'fas fa-file', children: null },
    //         { isHeader: false, newTab: false, name: 'Dashboard', link: 'dashboard', icon: 'fas fa-file', children: null },
    //     ]
    // },
]

onBeforeMount(_ => {
    if (userRole.value === 'admin') navigations.splice(0, 0, {
        isHeader: false,
        newTab: false,
        name: 'Users',
        link: 'users',
        icon: 'fas fa-user',
        children: null
    });
})

</script>

<style scoped></style>
