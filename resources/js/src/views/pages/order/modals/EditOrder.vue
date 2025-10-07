<template>
    <Teleport to=".teleport-modal">
        <div class="modal fade show" data-backdrop="static" id="editOrder" tabindex="-1" role="dialog"
            aria-labelledby="example1" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Edit Order </h4>
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
import putOrder from '../../../../composables/order/putOrder';
import getTables from '../../../../composables/table/getTables'
import $ from 'jquery'

const { updateOrder } = putOrder()
const { tables, loadTables } = getTables()
const props = defineProps(['modalData'])
const emit = defineEmits({ closeModal: false })
const name = ref(props.modalData.name)
let selectizeInstance


const handleSubmit = () => {

    const table = {
        id: props.modalData.id,
        customer_name: name.value,
        table_id: selectizeInstance.getValue(),
    }

    updateOrder(table).then(() => {
        $('#editOrder').modal('hide')
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
        selectizeInstance.setValue(props.modalData.table_id)
    })
        .catch()
})

onUnmounted(() => {
    selectizeInstance.destroy();
});

</script>


<style></style>
