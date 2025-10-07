<template>
    <div>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Product Variants</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Product Variants</li>
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
                                            <button @click="toggleAddProductVariantModal" data-toggle="modal"
                                                data-target="#addProductVariant" class="btn btn-primary">
                                                Add Product Variant
                                            </button>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" @keyup.enter="searchProducts" class="form-control "
                                                placeholder="Search.." v-model="search_term" autocomplete="off">
                                        </div>
                                    </div>

                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr class="table-info text-center">
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Quantity</th>
                                                <th>Rate</th>
                                                <th>Product</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="productVariant in  productVariants " :key="productVariant.id"
                                                class="text-center">
                                                <td><img :src="`${productVariant.image_url}?v=${Math.random()}`" alt="">
                                                </td>
                                                <td>{{ productVariant.name }}</td>
                                                <td>{{ productVariant.quantity }}</td>
                                                <td>Rs. {{ parseFloat(productVariant.rate) }}</td>
                                                <td>{{ productVariant.product_name }}</td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm"
                                                        @click="toggleEditProductVariantModal();
                                                        modalData = {
                                                            id: productVariant.id, name: productVariant.name,
                                                            quantity: productVariant.quantity, rate: productVariant.rate, product_id: productVariant.product_id,
                                                        }"
                                                        data-toggle="modal" data-target="#editProductVariant">
                                                        <span class="fa fa-edit"></span>
                                                    </button>
                                                    &nbsp;
                                                    <button class="btn btn-sm btn-danger"
                                                        @click=" deleteData(productVariant.id) " href="#">
                                                        <span class="fa fa-trash"></span>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                                <div class="card-footer">
                                    <Pagination :meta=" meta " routeName="productVariants" />
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
        <AddProductVariant v-if=" openAddProductVariantModal " @closeModal=" toggleAddProductVariantModal " />
        <EditProductVariant v-if=" openEditProductVariantModal " @closeModal=" toggleEditProductVariantModal "
            :modalData=" modalData " />
    </div>
</template>

<script setup>
import { inject, onMounted, ref, watch } from 'vue'
import AddProductVariant from './modals/AddProductVariant.vue'
import EditProductVariant from './modals/EditProductVariant.vue'
import getProductVariants from '../../../composables/productVariant/getProductVariants'
import deleteProductVariant from '../../../composables/productVariant/deleteProductVariant'
import Pagination from '../../../assets/Pagination.vue'
import { useRoute, useRouter } from 'vue-router'

const swal = inject('$swal')

const { productVariants, loadProductVariants, meta } = getProductVariants()
const { destroyProductVariant } = deleteProductVariant()
const route = useRoute()
const router = useRouter()

const modalData = ref([])
const openAddProductVariantModal = ref(false)
const openEditProductVariantModal = ref(false)
const search_term = ref('')
let searchTimeOut;

const toggleAddProductVariantModal = () => {
    openAddProductVariantModal.value = !openAddProductVariantModal.value
    loadProductVariants(route.query)
}

const toggleEditProductVariantModal = () => {
    openEditProductVariantModal.value = !openEditProductVariantModal.value
    loadProductVariants(route.query)
}

function deleteData(productVariant_id) {
    swal.fire({
        title: 'Delete Product ?',
        text: 'The operation cannot be reverted !!!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
    }).then(result => {
        if (result.isConfirmed) {
            destroyProductVariant(productVariant_id)
                .then(() => filterProductVariant(productVariant_id))
                .catch()
        }
    })
}

const filterProductVariant = (productVariant_id) => {
    productVariants.value = productVariants.value.filter(productVariant => productVariant.id !== productVariant_id)
}

const searchProducts = () => {
    if (search_term.value !== "")
        router.push({ name: 'productVariants', query: { search_term: search_term.value } })
    else router.push({ name: 'productVariants' })
}

watch(search_term, (newValue, oldValue) => {
    clearTimeout(searchTimeOut);
    searchTimeOut = setTimeout(searchProducts, 500);
})

watch(route, (newValue, oldValue) => {
    if(route.name === 'productVariants') loadProductVariants(route.query)
})

onMounted(() => {
    loadProductVariants(route.query)
})

</script>

<style scoped></style>
