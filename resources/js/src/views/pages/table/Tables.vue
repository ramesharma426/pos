<template>
    <div>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Tables</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Tables</li>
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
                                        <button @click="toggleAddTableModal" data-toggle="modal" data-target="#addTable"
                                            class="btn btn-primary">
                                            Add Table
                                        </button>
                                    </div>

                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr class="table-info text-center">
                                                <th>Number</th>
                                                <th>Capacity</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="table in  tables " :key="table.id" class="text-center">
                                                <td>{{ table.number }}</td>
                                                <td>{{ table.capacity }}</td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm"
                                                        @click="toggleEditTableModal();
                                                        modalData = { id: table.id, number: table.number, capacity: table.capacity }" data-toggle="modal"
                                                        data-target="#editTable">
                                                        <span class="fa fa-edit"></span>
                                                    </button>
                                                    &nbsp;
                                                    <button class="btn btn-sm btn-danger" @click=" deleteData(table.id) "
                                                        href="#">
                                                        <span class="fa fa-trash"></span>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

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
        <AddTable v-if=" openAddTableModal " @closeModal=" toggleAddTableModal " />
        <EditTable v-if=" openEditTableModal " @closeModal=" toggleEditTableModal " :modalData=" modalData " />
    </div>
</template>

<script setup>
import { inject, onMounted, ref } from 'vue'
import AddTable from './modals/AddTable.vue'
import EditTable from './modals/EditTable.vue'
import getTables from '../../../composables/table/getTables'
import deleteTable from '../../../composables/table/deleteTable'

const swal = inject('$swal')

const { tables, loadTables } = getTables()
const { destroyTable } = deleteTable()

const modalData = ref([])
const openAddTableModal = ref(false)
const openEditTableModal = ref(false)

const toggleAddTableModal = () => {
    openAddTableModal.value = !openAddTableModal.value
    loadTables()
}

const toggleEditTableModal = () => {
    openEditTableModal.value = !openEditTableModal.value
    loadTables()
}

function deleteData(table_id) {
    swal.fire({
        title: 'Delete Table ?',
        text: 'The operation cannot be reverted !!!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
    }).then(result => {
        if (result.isConfirmed) {
            destroyTable(table_id)
                .then(() => filterTables(table_id))
                .catch()
        }
    })
}

const filterTables = (table_id) => {
    tables.value = tables.value.filter(table => table.id !== table_id)
}

onMounted(() => {
    loadTables()
})

</script>

<style scoped></style>
