<template>
    <Teleport to=".teleport-modal">
        <div class="modal fade show" data-backdrop="static" id="editUserPassword" tabindex="-1" role="dialog"
            aria-labelledby="example1" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Edit User Password</h4>
                        <button type="button" class="close" @click="emit('closeModal')" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="handleSubmit">
                        <div class="modal-body">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label><span class="red-star">*</span> Password</label>
                                    <input type="password" class="form-control" placeholder="Password" v-model="password"
                                        required />
                                </div>

                                <div class="form-group row">
                                    <label><span class="red-star">*</span> Confirm Password</label>
                                    <input type="password" class="form-control" placeholder="Confirm Password"
                                        v-model="password_confirmation" required />
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
import putPassword from '../../../../composables/user/putPassword'

const password = ref('')
const password_confirmation = ref('')
const props = defineProps(['modelData'])
const emit = defineEmits({ closeModal: false })
const { updatePassword } = putPassword()


const handleSubmit = () => {
    updatePassword({
        id: props.modelData.id,
        password: password.value,
        password_confirmation: password_confirmation.value,
    }).then(() => {
        $('#editUserPassword').modal('hide')
        emit('closeModal')
    }).catch()
}
</script>


<style></style>
