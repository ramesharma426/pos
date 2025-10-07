<template>
    <Teleport to=".teleport-modal">
        <div class="modal fade show" data-backdrop="static" id="editProductVariant" tabindex="-1" role="dialog"
            aria-labelledby="example1" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Edit Product </h4>
                        <button type="button" class="close" @click="emit('closeModal')" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="handleSubmit">
                        <div class="modal-body">
                            <div class="col-12">

                                <div class="form-group row">
                                    <label><span class="red-star">*</span>Name</label>
                                    <input type="text" class="form-control" placeholder="Product Name" v-model="name"
                                        autocomplete="off" required>
                                </div>

                                <div class="form-group row">
                                    <label><span class="red-star">*</span>Quantity</label>
                                    <input type="number" min="1" class="form-control" placeholder="Quantity"
                                        v-model="quantity" autocomplete="off" required>
                                </div>

                                <div class="form-group row">
                                    <label><span class="red-star">*</span>Rate</label>
                                    <input type="number" min="0" step=".01" class="form-control" placeholder="Product Rate"
                                        v-model="rate" autocomplete="off" required>
                                </div>

                                <div class="form-group row">
                                    <label><span class="red-star">*</span>Product</label>
                                    <select class="form-control" id="product-select" required></select>
                                </div>

                                <div class="form-group row">
                                    <label>Attachment</label>
                                    <input type="file" @change="onFileSelected" class="form-control-file" accept="image/*,image/heic" >
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
import { onMounted, ref, onUnmounted } from 'vue';
import putProductVariant from '../../../../composables/productVariant/putProductVariant';
import getProductNames from '../../../../composables/product/getProductNames'
import $ from 'jquery'

const props = defineProps(['modalData'])
const { updateProductVariant } = putProductVariant()
const { productNames, loadProductNames } = getProductNames()
const emit = defineEmits({ closeModal: false })
const name = ref(props.modalData.name)
const rate = ref(props.modalData.rate)
const quantity = ref(props.modalData.quantity)
const attachment = ref(null)
let selectizeInstance

const onFileSelected = (event) => {
    attachment.value = event.target.files[0]
}

const handleSubmit = () => {

    const formData = new FormData()
    formData.append('id', props.modalData.id)
    formData.append('name', name.value)
    formData.append('quantity', quantity.value)
    formData.append('rate', rate.value)
    formData.append('product_id', selectizeInstance.getValue())

    if (attachment.value !== null)
        formData.append('attachment', attachment.value, attachment.value.name)

    updateProductVariant(formData).then(() => {
        $('#editProductVariant').modal('hide')
        emit('closeModal')
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
        selectizeInstance.setValue(props.modalData.product_id)
    }).catch()
})

onUnmounted(() => {
    selectizeInstance.destroy();
});

</script>


<style></style>
