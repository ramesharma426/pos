<template>
    <div>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>sales</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Sales</li>
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

                                        </div>

                                        <div class="col-3">
                                            <VDatePicker v-model="vdate" :masks="masks"
                                                         :select-attribute="selectAttribute">
                                                <template #default="{ inputValue, togglePopover }">
                                                    <input type="text" class="form-control" :value="inputValue"
                                                           @click="togglePopover"/>
                                                </template>
                                            </VDatePicker>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr class="table-info text-center">
                                            <th>Product Variant</th>
                                            <th>Quantity</th>
                                            <th>Cost Price</th>
                                            <th>Sales Price</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="sale in sales" :key="sale.id">
                                            <td>{{ sale.product_variant }}</td>
                                            <td class="text-right">{{ sale.quantity }}</td>
                                            <td class="text-right">{{ numbersWithCommas(parseFloat(sale.cost_price)) }}</td>
                                            <td class="text-right">{{ numbersWithCommas(parseFloat(sale.sales_price)) }}</td>
                                        </tr>
                                        <template v-if="discountByDate > 0">
                                            <tr>
                                                <td colspan="2"><b>Sub Total</b></td>
                                                <td class="text-right"><b>{{ numbersWithCommas(totalCostPrice) }}</b></td>
                                                <td class="text-right"><b>{{ numbersWithCommas(totalSalesPrice) }}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><b>Discount</b></td>
                                                <td class="text-right" colspan="2"><b>{{ numbersWithCommas(parseFloat(discountByDate)) }}</b></td>
                                            </tr>
                                        </template>
                                        <template v-if="totalSalesPrice > 0">
                                            <tr>
                                                <td colspan="2"><b>Total</b></td>
                                                <td class="text-right"><b>{{ numbersWithCommas(totalCostPrice) }}</b></td>
                                                <td class="text-right"><b>{{ numbersWithCommas(totalSalesPrice - discountByDate) }}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><b>Profit/Loss</b></td>
                                                <td class="text-right" colspan="2">
                                                    <b>{{
                                                            totalSalesPrice - totalCostPrice - discountByDate >= 0 ?
                                                                numbersWithCommas(totalSalesPrice - totalCostPrice - discountByDate) :
                                                                `( ${numbersWithCommas(Math.abs(totalSalesPrice - totalCostPrice - discountByDate))} )`
                                                        }}</b></td>
                                            </tr>
                                        </template>
                                        </tbody>
                                    </table>

                                    <div class="row justify-content-center">
                                        <div v-if="sales.length <= 0" class="mt-3"><b>No Data Found</b></div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- /. row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
    </div>
</template>

<script setup>
import getSales from "@/src/composables/sales/getSales"
import getDiscountByDate from "@/src/composables/payment/getPaymentByDate";
import {computed, onMounted, ref, watch} from "vue";
import {useRoute, useRouter} from 'vue-router'

const {sales, loadSales} = getSales()
const {discountByDate, loadDiscountByDate} = getDiscountByDate()
const route = useRoute()
const router = useRouter()

const vdate = ref(new Date())
const selectAttribute = ref({dot: true});

const masks = ref({
    input: 'YYYY-MM-DD',
});

watch(vdate, (newValue, oldValue) => {
    router.push({name: 'sales', query: {date: vdate.value.toISOString().slice(0, 10)}})
})

watch(route, (newValue, oldValue) => {
    if (route.name === 'sales')
        loadSales(route.query)
        loadDiscountByDate(route.query)
})

const totalCostPrice = computed(() => {
    return sales.value.reduce((total, sale) => (total + parseFloat(sale.cost_price)), 0)
});

const totalSalesPrice = computed(() => {
    return sales.value.reduce((total, sale) => (total + parseFloat(sale.sales_price)), 0)
});

const numbersWithCommas = (x) =>
    x.toString().split(".")[0].length > 3
        ? x
            .toString()
            .substring(0, x.toString().split(".")[0].length - 3)
            .replace(/\B(?=(\d{2})+(?!\d))/g, ",") +
        "," +
        x.toString().substring(x.toString().split(".")[0].length - 3)
        : x.toString();

onMounted(_ => {
    loadSales()
    loadDiscountByDate()
})


</script>

<style scoped>

</style>
