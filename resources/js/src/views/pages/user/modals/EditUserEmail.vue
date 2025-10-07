<template>
    <Teleport to=".teleport-modal">
        <div class="modal fade show" data-backdrop="static" id="editUserEmail" tabindex="-1" role="dialog"
            aria-labelledby="example1" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Edit User Email</h4>
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
                                    <input type="email" class="form-control" placeholder="email" v-model="email"
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
import putEmail from '../../../../composables/user/putEmail'

const { updateEmail } = putEmail()
const email = ref(props.modelData.email)
const props = defineProps(['modelData'])
const emit = defineEmits({ closeModal: false })

function handleSubmit() {
    updateEmail({
        id: props.modelData.id,
        email: email.value,
    }).then(() => {
        $('#editUserEmail').modal('hide')
        emit('closeModal')
    }).catch()
}


</script>


<style></style>
