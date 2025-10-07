<template>
    <Teleport to=".teleport-modal">
        <div class="modal fade show" data-backdrop="static" id="addOrder" tabindex="-1" role="dialog"
            aria-labelledby="example1" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Add Order </h4>
                        <button type="button" class="close" @click="emit('closeModal')" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="handleSubmit">
                        <div class="modal-body">
                            <div class="col-12">

                                <div class="form-group row">
                                    <label><span class="red-star">*</span>Table</label>
                                    <select class="form-control" id="table-select" required></select>
                                </div>

                                <div class="form-group row">
                                    <label>Name</label>
                                    <input type="text" class="form-control" placeholder="Customer Name" v-model="name"
                                        autocomplete="off">
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
import postOrder from '../../../../composables/order/postOrder';
import getTables from '../../../../composables/table/getTables'
import $ from 'jquery'

const { addOrder } = postOrder()
const { tables, loadTables } = getTables()
const emit = defineEmits({ closeModal: false })
const name = ref('')
let selectizeInstance


const handleSubmit = () => {

    const table = {
        customer_name: name.value,
        table_id: selectizeInstance.getValue(),
    }

    addOrder(table).then(() => {
        $('#addOrder').modal('hide')
        emit('closeModal')
    }).catch()
}

onMounted(() => {
    loadTables().then(() => {
        selectizeInstance = $("#table-select").selectize({
            placeholder: 'Select a table',
            valueField: "id",
            labelField: "number",
            maxItems: 1,
            options: tables.value,
            searchField: ["number"],
        })[0].selectize;
    }).catch()
})

onUnmounted(() => {
    selectizeInstance.destroy();
});

</script>


<style></style>
