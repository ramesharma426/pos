<template>
    <div>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Products</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Products</li>
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
                                            <button @click="toggleAddProductModal" data-toggle="modal"
                                                data-target="#addProduct" class="btn btn-primary">
                                                Add Product
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
                                                <th>Name</th>
                                                <th>Category</th>
                                                <th class="text-right">Stock</th>
                                                <th>Unit</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="product in  products " :key="product.id" class="text-center">
                                                <td>{{ product.name }}</td>
                                                <td>{{ product.category }}</td>
                                                <td class="text-right">{{ numbersWithCommas(product.stock) }}</td>
                                                <td>{{ product.unit }}</td>
                                                <td>

                                                    <button class="btn btn-primary btn-sm"
                                                        @click="
                                                            toggleEditProductModal();
                                                            modalData = { id: product.id, name: product.name, category_id: product.category_id, unit_id: product.unit_id, }
                                                        "
                                                        data-toggle="modal" data-target="#editProduct">
                                                        <span class="fa fa-edit"></span>
                                                    </button>
                                                    &nbsp;
                                                    <button class="btn btn-sm btn-danger" @click=" deleteData(product.id) "
                                                        href="#">
                                                        <span class="fa fa-trash"></span>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                                <div class="card-footer">
                                    <Pagination :meta=" meta " routeName="products" />
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
        <AddProduct v-if=" openAddProductModal " @closeModal=" toggleAddProductModal " />
        <EditProduct v-if=" openEditProductModal " @closeModal=" toggleEditProductModal " :modalData=" modalData " />
    </div>
</template>

<script setup>
import { inject, onMounted, ref, watch } from 'vue'
import AddProduct from './modals/AddProduct.vue'
import EditProduct from './modals/EditProduct.vue'
import getProducts from '../../../composables/product/getProducts'
import deleteProduct from '../../../composables/product/deleteProduct'
import Pagination from '../../../assets/Pagination.vue'
import { useRoute, useRouter } from 'vue-router'

const swal = inject('$swal')

const { products, loadProducts, meta } = getProducts()
const { destroyProduct } = deleteProduct()
const route = useRoute()
const router = useRouter()

const modalData = ref([])
const openAddProductModal = ref(false)
const openEditProductModal = ref(false)
const search_term = ref('')
let searchTimeOut;

const toggleAddProductModal = () => {
    openAddProductModal.value = !openAddProductModal.value
    loadProducts(route.query)
}

const toggleEditProductModal = () => {
    openEditProductModal.value = !openEditProductModal.value
    loadProducts(route.query)
}

function deleteData(product_id) {
    swal.fire({
        title: 'Delete Product ?',
        text: 'The operation cannot be reverted !!!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
    }).then(result => {
        if (result.isConfirmed) {
            destroyProduct(product_id)
                .then(() => filterProducts(product_id))
                .catch()
        }
    })
}

const filterProducts = (product_id) => {
    products.value = products.value.filter(product => product.id != product_id)
}

const searchProducts = () => {
    if (search_term.value != "")
        router.push({ name: 'products', query: { search_term: search_term.value } })
    else router.push({ name: 'products' })
}

 watch(search_term, (newValue, oldValue) => {
    clearTimeout(searchTimeOut);
    searchTimeOut = setTimeout(searchProducts, 500);
})

watch(route, (newValue, oldValue) => {
    if(route.name === 'products') loadProducts(route.query)
})

const numbersWithCommas = (x) =>
    x.toString().split(".")[0].length > 3
        ? x
            .toString()
            .substring(0, x.toString().split(".")[0].length - 3)
            .replace(/\B(?=(\d{2})+(?!\d))/g, ",") +
        "," +
        x.toString().substring(x.toString().split(".")[0].length - 3)
        : x.toString();


onMounted(() => {
    loadProducts(route.query)
})


</script>

<style scoped></style>
