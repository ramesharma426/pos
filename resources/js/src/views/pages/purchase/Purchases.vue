<template>
    <div>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Purchases</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Purchases</li>
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
                                            <button @click="toggleAddPurchaseModal" data-toggle="modal"
                                                    data-target="#addPurchase"
                                                    class="btn btn-primary">
                                                Add Purchase
                                            </button>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr class="table-info text-center">
                                            <th>Date</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                            <th class="text-right">Cost</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="purchase in purchases  " :key="purchase.id" class="text-center">
                                            <td>{{ purchase.created_at }}</td>
                                            <td>{{ purchase.product }}</td>
                                            <td>{{ purchase.quantity }}</td>
                                            <td>{{ purchase.unit }}</td>
                                            <td class="text-right">{{ numbersWithCommas(parseFloat(purchase.cost)) }}</td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <div class="row justify-content-center">
                                        <div v-if="purchases.length <= 0" class="mt-3"><b>No Data Found</b></div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <Pagination :meta="meta" routeName="purchases"/>
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
        <AddPurchase v-if=" openAddPurchaseModal " @closeModal=" toggleAddPurchaseModal "/>

    </div>
</template>

<script setup>

import {onMounted, ref, watch} from 'vue'
import getPurchases from "@/src/composables/purchase/getPurchases";
import Pagination from '../../../assets/Pagination.vue'
import AddPurchase from "@/src/views/pages/purchase/modals/AddPurchase.vue";
import {useRoute} from 'vue-router'

const {meta, purchases, loadPurchases} = getPurchases();
const route = useRoute()

const modelData = ref([])
const openAddPurchaseModal = ref(false)

const toggleAddPurchaseModal = () => {
    openAddPurchaseModal.value = !openAddPurchaseModal.value
    loadPurchases(route.query)
}

watch(route, (newValue, oldValue) => {
    if(route.name === 'purchases') loadPurchases(route.query)
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
    loadPurchases(route.query);
})

</script>

<style scoped></style>
