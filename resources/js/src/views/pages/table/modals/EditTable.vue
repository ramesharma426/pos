<template>
    <Teleport to=".teleport-modal">
        <div class="modal fade show" data-backdrop="static" id="editTable" tabindex="-1" role="dialog"
            aria-labelledby="example1" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Edit Table</h4>
                        <button type="button" class="close" @click="emit('closeModal')" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="handleSubmit">
                        <div class="modal-body">
                            <div class="col-12">

                                <div class="form-group row">
                                    <label><span class="red-star">*</span> Number</label>
                                    <input type="text" class="form-control" placeholder="Table Number" v-model="number"
                                        autocomplete="off" required>
                                </div>

                                <div class="form-group row">
                                    <label><span class="red-star">*</span> Capacity</label>
                                    <input type="number" min="1" class="form-control" placeholder="Table Capacity" v-model="capacity"
                                        autocomplete="off" required>
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
import { ref } from 'vue';
import putTable from '../../../../composables/table/putTable';

const { updateTable } = putTable()
const props = defineProps(['modalData'])
const number = ref(props.modalData.number)
const capacity = ref(props.modalData.capacity)
const emit = defineEmits({ closeModal: false })

const handleSubmit = () => {
    const table = { id: props.modalData.id, number: number.value, capacity: capacity.value }
    updateTable(table).then(() => {
        $('#editTable').modal('hide')
        emit('closeModal')
    }).catch()
}


</script>


<style></style>
