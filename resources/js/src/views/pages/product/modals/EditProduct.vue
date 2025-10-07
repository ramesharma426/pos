<template>
    <Teleport to=".teleport-modal">
        <div class="modal fade show" data-backdrop="static" id="editProduct" tabindex="-1" role="dialog"
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
                                    <label><span class="red-star">*</span>Category</label>
                                    <select class="form-control" id="category-select" required></select>
                                </div>

                                <div class="form-group row">
                                    <label><span class="red-star">*</span>Unit</label>
                                    <select class="form-control" v-model="unit_id" required>
                                        <option v-for="unit in units" :key="unit.id" :value="unit.id">{{
                                            unit.name }}
                                        </option>
                                    </select>
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
import putProduct from '../../../../composables/product/putProduct';
import getCategories from '../../../../composables/category/getCategories'
import getUnits from '../../../../composables/unit/getUnits'
import $ from 'jquery'

const { updateProduct } = putProduct()
const { categories, loadCategories } = getCategories()
const { units, loadUnits } = getUnits()
const emit = defineEmits({ closeModal: false })
const props = defineProps(['modalData'])
const name = ref(props.modalData.name)
const unit_id = ref(props.modalData.unit_id)
let selectizeInstance

const handleSubmit = () => {

    const product = {
        id: props.modalData.id,
        name: name.value,
        category_id: selectizeInstance.getValue(),
        unit_id: unit_id.value
    }

    updateProduct(product).then(() => {
        $('#editProduct').modal('hide')
        emit('closeModal')
    }).catch()
}

onMounted(() => {
    loadCategories().then(() => {
        selectizeInstance = $("#category-select").selectize({
            placeholder: 'Select a category',
            valueField: "id",
            labelField: "name",
            maxItems: 1,
            options: categories.value,
            searchField: ["name"],
        })[0].selectize;
        selectizeInstance.setValue(props.modalData.category_id)
    }).catch()

    loadUnits()
})

onUnmounted(() => {
    selectizeInstance.destroy();
});

</script>


<style></style>
