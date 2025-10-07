<template>
    <div>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Users</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Users</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="card">
                                <div class="card-header">

                                    <div class="row justify-content-between">
                                        <div class="col-4">
                                            <button @click="toggleAddUserModal" data-toggle="modal" data-target="#addUser"
                                                class="btn btn-primary">
                                                Add User
                                            </button>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" @keyup.enter="searchUsers" class="form-control "
                                                placeholder="Search.." v-model="search_term" autocomplete="off">
                                        </div>
                                    </div>

                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr class="table-info text-center">
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="user in   users  " :key="user.id" class="text-center">
                                                <td>{{ user.name }}</td>
                                                <td>{{ user.email }}</td>
                                                <td>{{ user.role }}</td>
                                                <td>
                                                    <div class="btn-group">

                                                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <span class="fa fa-edit"></span>
                                                        </button>

                                                        <div class="dropdown-menu">

                                                            <a href="#" class="dropdown-item"
                                                                @click="toggleEditUserNameModal(); modelData = { id: user.id, name: user.name }"
                                                                data-toggle="modal" data-target="#editUserName">Name</a>

                                                            <a href="#" class="dropdown-item"
                                                                @click=" toggleEditUserEmailModal(); modelData = { id: user.id, email: user.email } "
                                                                data-toggle="modal" data-target="#editUserEmail">Email</a>

                                                            <a href="#" class="dropdown-item"
                                                                @click=" toggleEditUserPasswordModal(); modelData = { id: user.id } "
                                                                data-toggle="modal"
                                                                data-target="#editUserPassword">Password</a>

                                                            <a href="#" class="dropdown-item"
                                                                @click=" toggleEditUserRoleModal(); modelData = { id: user.id, role_id: user.role_id } "
                                                                data-toggle="modal" data-target="#editUserRole">Role</a>

                                                        </div>
                                                    </div>
                                                    &nbsp;
                                                    <button class="btn btn-sm btn-danger" @click=" deleteData(user.id) "
                                                        href="#">
                                                        <span class="fa fa-trash"></span>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                                <div class="card-footer">
                                    <Pagination :meta=" meta " routeName="users" />
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- /. row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>

        <!-- modals -->
        <AddUser v-if=" openAddUserModal " @closeModal=" toggleAddUserModal " />
        <EditUserName v-if=" openEditUserNameModal " @closeModal=" toggleEditUserNameModal " :modelData=" modelData " />
        <EditUserEmail v-if=" openEditUserEmailModal " @closeModal=" toggleEditUserEmailModal " :modelData=" modelData " />
        <EditUserRole v-if=" openEditUserRoleModal " @closeModal=" toggleEditUserRoleModal " :modelData=" modelData " />
        <EditUserPassword v-if=" openEditUserPasswordModal " @closeModal=" toggleEditUserPasswordModal "
            :modelData=" modelData " />
    </div>
</template>

<script setup>

import { inject, onMounted, ref, watch } from 'vue'
import AddUser from './modals/AddUser.vue';
import EditUserName from './modals/EditUserName.vue';
import EditUserEmail from './modals/EditUserEmail.vue';
import EditUserRole from './modals/EditUserRole.vue';
import EditUserPassword from './modals/EditUserPassword.vue';
import Pagination from '../../../assets/Pagination.vue'
import getUsers from '../../../composables/user/getUsers'
import deleteUser from '../../../composables/user/deleteUser'
import { useRoute, useRouter } from 'vue-router'

const { users, loadUsers, meta } = getUsers()
const { destroyUser } = deleteUser()
const route = useRoute()
const router = useRouter()

const modelData = ref([])
const search_term = ref('')
const openAddUserModal = ref(false)
const openEditUserNameModal = ref(false)
const openEditUserEmailModal = ref(false)
const openEditUserPasswordModal = ref(false)
const openEditUserRoleModal = ref(false)
let searchTimeOut;

const swal = inject('$swal')

const toggleAddUserModal = () => {
    openAddUserModal.value = !openAddUserModal.value
    loadUsers(route.query)
}

const toggleEditUserNameModal = () => {
    openEditUserNameModal.value = !openEditUserNameModal.value
    loadUsers(route.query)
}

const toggleEditUserEmailModal = () => {
    openEditUserEmailModal.value = !openEditUserEmailModal.value
    loadUsers(route.query)
}

const toggleEditUserPasswordModal = () => {
    openEditUserPasswordModal.value = !openEditUserPasswordModal.value
}

const toggleEditUserRoleModal = () => {
    openEditUserRoleModal.value = !openEditUserRoleModal.value
    loadUsers(route.query)
}

const deleteData = (user_id) => {
    swal.fire({
        title: 'Do you want to delete this user ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
    }).then(result => {
        if (result.isConfirmed) {
            destroyUser(user_id).then(() => { filterUsers(user_id) }).catch()
        }
    })
}

const filterUsers = (user_id) => {
    users.value = users.value.filter((user) => user.id != user_id)
}

const searchUsers = () => {
    if (search_term.value !== "")
        router.push({ name: 'users', query: { search_term: search_term.value } })
    else router.push({ name: 'users' })
}

watch(search_term, (newValue, oldValue) => {
    clearTimeout(searchTimeOut);
    searchTimeOut = setTimeout(searchUsers, 500);
})

watch(route, (newValue, oldValue) => {
    if(route.name === 'users') loadUsers(route.query)
})

onMounted(() => {
    loadUsers(route.query);
})

</script>

<style scoped></style>
