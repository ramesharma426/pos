<template>
    <Teleport to=".teleport-modal">
        <div class="modal fade show" data-backdrop="static" id="addUser" tabindex="-1" role="dialog"
            aria-labelledby="example1" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Add User </h4>
                        <button type="button" class="close" @click="emit('closeModal')" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="handleSubmit">
                        <div class="modal-body">

                            <div class="col-12">
                                <div class="form-group row">
                                    <label><span class="red-star">*</span> Name</label>
                                    <input type="text" class="form-control" placeholder="Name" v-model="name"
                                        autocomplete="off" required>
                                </div>
                                <div class="form-group row">
                                    <label><span class="red-star">*</span> Email</label>
                                    <input type="email" class="form-control" placeholder="Email" v-model="email" required />
                                </div>

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

                                <div class="form-group row">
                                    <label><span class="red-star">*</span> Role</label>
                                    <select class="form-control" v-model="role" required>
                                        <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}
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

import { onMounted, ref } from 'vue';
import getRoles from '../../../../composables/role/getRoles'
import postUser from '../../../../composables/user/postUser';
import $ from 'jquery'

const { roles, loadRoles } = getRoles()
const { addUser } = postUser()

const emit = defineEmits({ closeModal: false })
const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const role = ref('')

const handleSubmit = () => {
    const user = {
        name: name.value,
        email: email.value,
        password: password.value,
        password_confirmation: password_confirmation.value,
        role_id: role.value
    }

    addUser(user).then(() => {
        emit('closeModal')
        $('#addUser').modal('hide')
    }).catch()
}

onMounted(() => { loadRoles() })

</script>


<style></style>
