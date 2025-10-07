<template>
    <Teleport to=".teleport-modal">
        <div class="modal fade show" data-backdrop="static" id="addOrderItem" tabindex="-1" role="dialog"
            aria-labelledby="example1" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Add Order Item </h4>
                        <button type="button" class="close" @click="emit('closeModal')" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="handleSubmit">
                        <div class="modal-body">
                            <div class="col-12">

                                <div class="form-group row">
                                    <label><span class="red-star">*</span>Product</label>
                                    <select class="form-control" id="product-variant-select" required></select>
                                </div>

                                <div class="form-group row">
                                    <label><span class="red-star">*</span>Quantity</label>
                                    <input type="number" min="1" class="form-control" placeholder="Product Quantity"
                                        v-model="quantity" autocomplete="off" required>
                                </div>

                                <label class="checkbox-label">
                                    <input class="checkbox-lg" type="checkbox" v-model="delivered_at" />
                                    <span class="font-weight-normal">Delivered</span>
                                </label>

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
import { onMounted, onUnmounted, ref } from 'vue';
import getProductVariantNames from '../../../../composables/productVariant/getProductVariantNames'
import postOrderItem from '../../../../composables/orderItem/postOrderItem'
import $ from 'jquery'

const { productVariantNames, loadProductVariantNames } = getProductVariantNames()
const props = defineProps(['modalData'])
const { addOrderItem } = postOrderItem()
const emit = defineEmits({ closeModal: false })
const quantity = ref('')
const delivered_at = ref(false)
let selectizeInstance


const handleSubmit = () => {

    const orderItem = {
        product_variant_id: selectizeInstance.getValue(),
        order_id: props.modalData.order_id,
        quantity: quantity.value,
        delivered_at: delivered_at.value,
    }

    addOrderItem(orderItem).then(() => {
        selectizeInstance.setValue('');
        quantity.value = ''
        delivered_at.value = false
        selectizeInstance.focus()
    }).catch()
}

onMounted(() => {
    loadProductVariantNames().then(() => {
        selectizeInstance = $("#product-variant-select").selectize({
            placeholder: 'Select a product variant',
            valueField: "id",
            labelField: "name",
            maxItems: 1,
            options: productVariantNames.value,
            searchField: ["name"],
        })[0].selectize;
    }).catch()
})

onUnmounted(() => {
    selectizeInstance.destroy();
});

</script>


<style scoped>
.checkbox-label {
    cursor: pointer;
}

.checkbox-lg {
    top: .8rem;
    scale: 1.4;
    margin-right: 0.7rem;
}
</style>
