<template>
    <Teleport to=".teleport-modal">
        <div class="modal fade show" data-backdrop="static" id="addTable" tabindex="-1" role="dialog"
            aria-labelledby="example1" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Add Table </h4>
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
                                    <input type="text" class="form-control" placeholder="Number" v-model="number"
                                        autocomplete="off" required>
                                </div>

                                <div class="form-group row">
                                    <label><span class="red-star">*</span> Capacity</label>
                                    <input type="number" min="1" class="form-control" placeholder="Capacity" v-model="capacity" required />
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
import postTable from '../../../../composables/table/postTable';
import $ from 'jquery'

const { addTable } = postTable()

const emit = defineEmits({ closeModal: false })
const number = ref('')
const capacity = ref('')

const handleSubmit = () => {
    const table = {
        number: number.value,
        capacity: capacity.value,
    }
    addTable(table).then(() => {
        emit('closeModal')
        $('#addTable').modal('hide')
    }).catch()
}

</script>


<style></style>
