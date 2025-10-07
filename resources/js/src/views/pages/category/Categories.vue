<template>
    <div>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Categories</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Categories</li>
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
                                    <button @click="toggleAddCategoryModal" data-toggle="modal" data-target="#addCategory"
                                        class="btn btn-primary">
                                        Add Category
                                    </button>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr class="table-info text-center">
                                                <th>Name</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="category in    categories   " :key="category.id" class="text-center">
                                                <td>{{ category.name }}</td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm" @click="toggleEditCategoryModal();
                                                    modelData = { id: category.id, name: category.name }"
                                                        data-toggle="modal" data-target="#editCategory">
                                                        <span class="fa fa-edit"></span>
                                                    </button>
                                                    &nbsp;
                                                    <button class="btn btn-sm btn-danger" @click=" deleteData(category.id) "
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
        <AddCategory v-if=" openAddCategoryModal " @closeModal=" toggleAddCategoryModal " />
        <EditCategory v-if=" openEditCategoryModal " @closeModal=" toggleEditCategoryModal " :modelData=" modelData " />
    </div>
</template>

<script setup>
import { inject, onMounted, ref } from 'vue'
import AddCategory from './modals/AddCategory.vue'
import EditCategory from './modals/EditCategory.vue'
import getCategories from '../../../composables/category/getCategories'
import deleteCategory from '../../../composables/category/deleteCategory'

const swal = inject('$swal')

const { categories, loadCategories } = getCategories()
const { destroyCategory } = deleteCategory()

const modelData = ref([])
const openAddCategoryModal = ref(false)
const openEditCategoryModal = ref(false)

const toggleAddCategoryModal = () => {
    openAddCategoryModal.value = !openAddCategoryModal.value
    loadCategories()
}

const toggleEditCategoryModal = () => {
    openEditCategoryModal.value = !openEditCategoryModal.value
    loadCategories()
}

function deleteData(category_id) {
    swal.fire({
        title: 'Do you want to delete this Category ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
    }).then(result => {
        if (result.isConfirmed) {
            destroyCategory(category_id)
                .then(() => filterCategory(category_id))
                .catch()
        }
    })
}

function filterCategory(category_id) {
    categories.value = categories.value.filter(category => category.id !== category_id)
}

onMounted(() => {
    loadCategories()
})

</script>

<style scoped></style>
