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
                                <li class="breadcrumb-item active">Units</li>
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
                                    <button @click="toggleAddUnitModal" data-toggle="modal" data-target="#addUnit"
                                        class="btn btn-primary">
                                        Add Unit
                                    </button>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr class="table-info text-center">
                                                <th>Unit</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="unit in  units " :key="unit.id" class="text-center">
                                                <td>{{ unit.name }}</td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm" @click="toggleEditUnitModal();
                                                    modelData = { id: unit.id, name: unit.name }" data-toggle="modal"
                                                        data-target="#editUnit">
                                                        <span class="fa fa-edit"></span>
                                                    </button>
                                                    &nbsp;
                                                    <button class="btn btn-sm btn-danger" @click=" deleteData(unit.id) "
                                                        href="#">
                                                        <span class="fa fa-trash"></span>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                                <div class="card-footer">

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
        <AddUnit v-if=" openAddUnitModal " @closeModal=" toggleAddUnitModal " />
        <EditUnit v-if=" openEditUnitModal " @closeModal=" toggleEditUnitModal " :modelData=" modelData " />
    </div>
</template>

<script setup>
import { inject, onMounted, ref } from 'vue'
import AddUnit from './modals/AddUnit.vue'
import EditUnit from './modals/EditUnit.vue'
import getUnits from "../../../composables/unit/getUnits"
import deleteUnit from '../../../composables/unit/deleteUnit'
const swal = inject('$swal')
const { units, loadUnits } = getUnits()
const { destroyUnit } = deleteUnit()

const modelData = ref([])
const openAddUnitModal = ref(false)
const openEditUnitModal = ref(false)

const toggleAddUnitModal = () => {
    openAddUnitModal.value = !openAddUnitModal.value
    loadUnits()
}

const toggleEditUnitModal = () => {
    openEditUnitModal.value = !openEditUnitModal.value
    loadUnits()
}

const deleteData = (unit_id) => {
    swal.fire({
        title: 'Do you want to delete this Unit ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
    }).then(result => {
        if (result.isConfirmed) {
            destroyUnit(unit_id).then(() => filterUnit(unit_id)).catch()
        }
    })
}

function filterUnit(unit_id) {
    units.value = units.value.filter((unit) => unit.id != unit_id)
}

onMounted(() => {
    loadUnits()
})

</script>

<style scoped></style>
