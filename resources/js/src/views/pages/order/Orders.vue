<template>
    <div>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Orders</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Orders</li>
                            </ol>
                        </div>
                    </div>
                </div>
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
                                            <button @click="toggleAddOrderModal" data-toggle="modal" data-target="#addOrder"
                                                class="btn btn-primary">
                                                Add Order
                                            </button>

                                        </div>
                                        <div class="col-3">
                                            <input type="text" @keyup.enter="searchOrders" class="form-control "
                                                placeholder="Search.." v-model="search_term" autocomplete="off">
                                        </div>
                                    </div>

                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr class="table-info text-center">
                                                <th>Name</th>
                                                <th>Table No.</th>
                                                <th>Order No.</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template v-for="order in orders" :key="order.id">
                                                <tr class="text-center main-tr" @click="handleTableClick($event, order.id)">
                                                    <td>{{ order.customer_name }}</td>
                                                    <td>{{ order.table_number }}</td>
                                                    <td>{{ order.id }}</td>
                                                    <td>{{ order.created_at.toString() }}</td>
                                                    <td>
                                                        <template v-if="!order.bill_number">
                                                            <button class="btn btn-sm btn-success"
                                                                data-target="#addOrderItem" data-toggle="modal" @click="toggleAddOrderItemModal();
                                                                modalData = { order_id: order.id }"
                                                                title="Add Order Item">
                                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                                            </button>
                                                            &nbsp;
                                                            <button class="btn btn-sm btn-secondary"
                                                                @click=" printBill(order)">
                                                                <i class="fa fa-print" aria-hidden="true"></i>
                                                            </button>
                                                            &nbsp;
                                                            <button class="btn btn-primary btn-sm" title="Edit order"
                                                                @click="
                                                                    toggleEditOrderModal();
                                                                modalData = {
                                                                    id: order.id, name: order.customer_name, table_id: order.table_id
                                                                }
                                                                    " data-toggle="modal" data-target="#editOrder">
                                                                <span class="fa fa-edit"></span>
                                                            </button>
                                                            &nbsp;
                                                            <button class="btn btn-sm btn-danger" title="Delete order"
                                                                @click=" deleteData(order.id)">
                                                                <span class="fa fa-trash"></span>
                                                            </button>
                                                        </template>
                                                        <template v-else>
                                                            <h5 class="d-inline"><span class="badge badge-success">{{
                                                                order.bill_number
                                                            }}</span></h5>
                                                            &nbsp;
                                                            <button class="btn btn-sm btn-secondary"
                                                                @click=" rePrintBill(order.id)">
                                                                <i class="fa fa-print" aria-hidden="true"></i>
                                                            </button>
                                                        </template>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7">
                                                        <div class="collapse-div collapse" :id="`collapse-${order.id}`">
                                                            <table class="table table-bordered table-hover">
                                                                <thead>
                                                                    <tr class="table-secondary">
                                                                        <th>Product Variant</th>
                                                                        <th class="text-right">Quantity</th>
                                                                        <th class="text-right">Rate</th>
                                                                        <th class="text-right">Amount</th>
                                                                        <th width="20%" >Delivered</th>
                                                                        <template v-if="!order.bill_number">
                                                                            <th class="text-center">
                                                                                Cancel
                                                                            </th>
                                                                        </template>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <template v-for="orderItem in orderItems "
                                                                        :key="orderItem.id">
                                                                        <tr>
                                                                            <td>{{ orderItem.product_variant }}</td>
                                                                            <td class="text-right">{{ orderItem.quantity }}</td>
                                                                            <td class="text-right">{{ parseFloat(orderItem.rate) }}</td>
                                                                            <td class="text-right">{{ numbersWithCommas(orderItem.quantity * orderItem.rate) }}
                                                                            </td>
                                                                            <template v-if="orderItem.delivered_at === ''">
                                                                                <td>
                                                                                    <label class="checkbox-label">
                                                                                        <input
                                                                                            @change=" deliverItem($event, orderItem.id, order.id)"
                                                                                            class="checkbox-lg"
                                                                                            type="checkbox"
                                                                                            :id="`delivered-at-${orderItem.id}`" />
                                                                                        <span
                                                                                            class="font-weight-normal">Delivered</span>
                                                                                    </label>
                                                                                </td>
                                                                            </template>
                                                                            <template v-else>
                                                                                <td>{{ orderItem.delivered_at }}</td>
                                                                            </template>
                                                                            <template v-if="!order.bill_number">
                                                                                <td class="text-center">
                                                                                    <button
                                                                                        @click=" cancelItem(orderItem.id)"
                                                                                        class="btn btn-sm btn-danger"
                                                                                        title="cancel order item">
                                                                                        <i class="fa fa-window-close"
                                                                                            aria-hidden="true"></i>
                                                                                    </button>
                                                                                </td>
                                                                            </template>
                                                                        </tr>
                                                                    </template>
                                                                    <template v-if="order.discount">
                                                                        <tr>
                                                                            <td colspan="4"><b>Sub Total</b></td>
                                                                            <td colspan="2"><b>{{ numbersWithCommas(totalAmount) }}</b></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="4"><b>Discount</b></td>
                                                                            <td colspan="2"><b>({{ numbersWithCommas(order.discount) }})</b>
                                                                            </td>
                                                                        </tr>
                                                                    </template>
                                                                    <tr>
                                                                        <td colspan="4"><b>Total</b></td>
                                                                        <td colspan="2"><b>Rs. {{ numbersWithCommas(totalAmount - order.discount) }}</b></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>

                                </div>
                                <div class="card-footer">
                                    <Pagination :meta="meta" routeName="orders" />
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
        <AddOrder v-if="openAddOrderModal" @closeModal="toggleAddOrderModal" />
        <EditOrder v-if="openEditOrderModal" @closeModal="toggleEditOrderModal" :modalData="modalData" />
        <AddOrderItem v-if="openAddOrderItemModal" @closeModal="toggleAddOrderItemModal" :modalData="modalData" />
    </div>
</template>

<script setup>
import { inject, onMounted, ref, watch, computed } from 'vue'
import connectAndPrint from '../../../helpers/thermalPrinter'
import AddOrder from './modals/AddOrder.vue'
import EditOrder from './modals/EditOrder.vue'
import AddOrderItem from './modals/AddOrderItems.vue'
import getOrders from '../../../composables/order/getOrders'
import getOrderItems from '../../../composables/orderItem/getOrderItemsById'
import deleteOrder from '../../../composables/order/deleteOrder'
import { deliverOrderItem } from '@/src/composables/orderItem/getDeliverOrderItem'
import { cancelOrderItem } from '@/src/composables/orderItem/getCancelOrderItem'
import postPayment from '../../../composables/payment/postPayment'
import getBillDetail from '../../../composables/payment/getReprintBill'
import Pagination from '../../../assets/Pagination.vue'
import { useRoute, useRouter } from 'vue-router'
import $ from 'jquery'

const swal = inject('$swal')

const { orders, loadOrders, meta } = getOrders()
const { destroyOrder } = deleteOrder()
const { orderItems, loadOrderItems } = getOrderItems()
const { payment, addPayment } = postPayment()
const { billDetail, loadBillDetail } = getBillDetail()
const route = useRoute()
const router = useRouter()

const modalData = ref([])
const openAddOrderItemModal = ref(false)
const openAddOrderModal = ref(false)
const openEditOrderModal = ref(false)
const search_term = ref('')
let searchTimeOut;

const totalAmount = computed(() => {
    return orderItems.value.reduce((total, orderItem) => (orderItem.delivered_at.length > 1 ? total + (orderItem.quantity * orderItem.rate) : total), 0)
});

const toggleAddOrderModal = () => {
    openAddOrderModal.value = !openAddOrderModal.value
    loadOrders(route.query)
}

const toggleEditOrderModal = () => {
    openEditOrderModal.value = !openEditOrderModal.value
    loadOrders(route.query)
}

const toggleAddOrderItemModal = () => {
    openAddOrderItemModal.value = !openAddOrderItemModal.value
    if (!openAddOrderItemModal.value)
        loadOrderItems(modalData.value.order_id)
}

const handleTableClick = (event, order_id) => {
    let collapseDiv = `#collapse-${order_id}`
    if (event.target.tagName === 'TD') {
        if (!$(collapseDiv).attr('class').split(" ").includes('show'))
            loadOrderItems(order_id).then(() => {
                $('.collapse-div').removeClass('show')
                $(collapseDiv).collapse('show')
                $('.main-tr').removeClass('table-danger')
                event.srcElement.parentElement.classList.add('table-danger')
            }).catch();
        else {
            $(collapseDiv).collapse('hide')
            event.srcElement.parentElement.classList.remove('table-danger')
        }
    }
}

const deliverItem = (event, orderItem_id, order_id) => {
    if (event.srcElement.checked) {
        deliverOrderItem(orderItem_id).then(() => {
            event.target.disabled = true
            loadOrderItems(order_id)
        }).catch()
    }
}

const cancelItem = (orderItem_id) => {
    swal.fire({
        title: 'Cancel Order Item ?',
        text: 'The operation cannot be reverted !!!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
    }).then(result => {
        if (result.isConfirmed) {
            cancelOrderItem(orderItem_id).then(() => filterOrderItems(orderItem_id)).catch()
        }
    })
}

const filterOrderItems = (orderItem_id) => {
    orderItems.value = orderItems.value.filter(orderItem => orderItem.id !== orderItem_id)
}

const deleteData = (order_id) => {
    swal.fire({
        title: 'Delete Order ?',
        text: 'The operation cannot be reverted !!!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
    }).then(result => {
        if (result.isConfirmed) {
            destroyOrder(order_id).then(() => filterOrders(order_id)).catch()
        }
    })
}

const filterOrders = order_id => orders.value = orders.value.filter(order => order.id !== order_id)

const searchOrders = () => {
    if (search_term.value !== "")
        router.push({ name: 'orders', query: { search_term: search_term.value } })
    else router.push({ name: 'orders' })
}

watch(search_term, (newValue, oldValue) => {
    clearTimeout(searchTimeOut);
    searchTimeOut = setTimeout(searchOrders, 500);
})

watch(route, (newValue, oldValue) => {
    if (route.name === 'orders') loadOrders(route.query)
})

const rePrintBill = (order_id) => {
    swal.fire({
        title: `Reprint Bill Order No. ${order_id} ?`,
        showCancelButton: true,
        confirmButtonText: 'Yes',
    }).then((result) => {
        if (result.isConfirmed) {
            loadOrderItems(order_id).then(() => {
                loadBillDetail(order_id).then(() => {
                    "usb" in navigator ? connectAndPrint(orderItems.value, totalAmount.value, billDetail.value) : swal.fire('WebUSB API is not available.')
                }).catch()
            }).catch()
        }
    })
}

const printBill = (order) => {

    swal.fire({

        title: `Print Bill Order No. ${order.id} ?`,
        input: 'number',
        inputLabel: 'Discount',
        inputValue: 0,
        inputValidator: value => {
            if (value < 0 || value > 99999) return 'Invalid Input'
        },
        showCancelButton: true,
        confirmButtonText: 'Yes',
    }).then(result => {
        if (result.isConfirmed) {

            loadOrderItems(order.id).then(() => {
                let includesUndelivered = orderItems.value.some((orderItem) => orderItem.delivered_at.length === 0)

                if (includesUndelivered) {
                    swal.fire('Undelivered Items in list')
                    return
                }

                if (orderItems.value.length <= 0) {
                    swal.fire('No Item in list')
                    return
                }

                let discount = result.value === "" ? 0 : parseFloat(result.value).toFixed(2)

                addPayment({ order_id: order.id, discount: discount }).then(() => {
                    const paymentDetails = { ...order, bill_number: payment.value.bill_number, discount: discount, bill_date: payment.value.bill_date }
                    "usb" in navigator ? connectAndPrint(orderItems.value, totalAmount.value, paymentDetails) : swal.fire('WebUSB API is not available.')
                    loadOrders(route.query)
                }).catch()

            }).catch()
        }
    })
}

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
    loadOrders(route.query)
})


</script>

<style scoped>
tr.text-center:hover {
    cursor: pointer;
}

.checkbox-label {
    cursor: pointer;
}

.checkbox-lg {
    top: .8rem;
    scale: 1.4;
    margin-right: 0.7rem;
}
</style>
