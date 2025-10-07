<template>
    <Teleport to=".teleport-modal">
        <div class="modal fade show" data-backdrop="static" id="addPurchase" tabindex="-1" role="dialog"
             aria-labelledby="example1" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Add Purchase </h4>
                        <button type="button" class="close" @click="emit('closeModal')" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="handleSubmit">
                        <div class="modal-body">

                            <div class="form-group row">
                                <label><span class="red-star">*</span>Product</label>
                                <select class="form-control" id="product-select" required></select>
                            </div>

                            <div class="col-12">
                                <div class="form-group row">
                                    <label><span class="red-star">*</span> Quantity</label>
                                    <input type="number" min="1" class="form-control" placeholder="quantity" v-model="quantity"
                                           autocomplete="off" required>
                                </div>
                                <div class="form-group row">
                                    <label><span class="red-star">*</span> Cost</label>
                                    <input type="number" min="1" step="0.01" class="form-control" placeholder="Total Cost" v-model="cost" required />
                                </div>

                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-danger" @click="emit('closeModal')"
                                    data-dismiss="modal">Close</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </Teleport>
</template>


<script setup>

import {onMounted, onUnmounted, ref} from 'vue';
import postPurchase from "@/src/composables/purchase/postPurchase";
import getProductNames from "@/src/composables/product/getProductNames";
import $ from 'jquery'

const {addPurchase} = postPurchase()
const {productNames, loadProductNames} = getProductNames()

const emit = defineEmits({ closeModal: false })
const cost = ref("")
const quantity = ref("")
let selectizeInstance
const handleSubmit = () => {
    const purchase = {
        product_id: selectizeInstance.getValue(),
        quantity: quantity.value,
        cost: cost.value
    }

    addPurchase(purchase).then(() => {
        emit('closeModal')
        $('#addPurchase').modal('hide')
    }).catch()
}

onMounted(() => {
    loadProductNames().then(() => {
        selectizeInstance = $("#product-select").selectize({
            placeholder: 'Select a product',
            valueField: "id",
            labelField: "name",
            maxItems: 1,
            options: productNames.value,
            searchField: ["name"],
        })[0].selectize;
    }).catch()
})

onUnmounted(() => {
    selectizeInstance.destroy();
});

</script>


<style></style>
