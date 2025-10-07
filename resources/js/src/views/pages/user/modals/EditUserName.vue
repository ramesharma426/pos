<template>
    <Teleport to=".teleport-modal">
        <div class="modal fade show" data-backdrop="static" id="editUserName" tabindex="-1" role="dialog"
            aria-labelledby="example1" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Edit User Name</h4>
                        <button type="button" class="close" @click="emit('closeModal')" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="handleSubmit">
                        <div class="modal-body">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label>Name</label>
                                    <input type="text" class="form-control" placeholder="Name" v-model="name"
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
import $ from 'jquery'
import putName from '../../../../composables/user/putName'

const props = defineProps(['modelData'])
const name = ref(props.modelData.name)
const emit = defineEmits({ closeModal: false })
const { updateName } = putName()


const handleSubmit = () => {
    updateName({
        id: props.modelData.id,
        name: name.value,
    }).then(() => {
        $('#editUserName').modal('hide')
        emit('closeModal')
    }).catch()
}

</script>


<style></style>
