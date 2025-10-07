<template>
    <router-link :to="{ name: link }" class="nav-link">
        <i :class="'nav-icon ' + icon"></i>
        <p>
            {{ name }}
            <i v-if="children" class="right fas fa-angle-left"></i>
        </p>
    </router-link>

    <ul v-if="children" class="nav nav-treeview">
        <li v-for="child in children" class="nav-item">
            <NavbarLinks :new-tab="child.newTab" :link="child.link" :children="child.children" :icon="child.icon"
                :name="child.name" />
        </li>
    </ul>
</template>

<script setup>
const props = defineProps(['name', 'link', 'icon', 'isHeader', 'newTab', 'children'])

</script>

<style scoped>
a.router-link-active {
    background-color: #007bff;
    color: #fff;
}

a.router-link-active.router-link-exact-active {
    background-color: rgba(255, 255, 255, .9);
    color: #343a40;
}
</style>