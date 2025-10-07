<template>
    <Teleport to=".teleport-modal">
        <div class="modal fade show" data-backdrop="static" id="editUserRole" tabindex="-1" role="dialog"
            aria-labelledby="example1" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Edit User Role</h4>
                        <button type="button" class="close" @click="emit('closeModal')" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="handleSubmit">
                        <div class="modal-body">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label>Role</label>
                                    <select class="form-control" v-model="role_id" required>
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
import putRole from '../../../../composables/user/putRole'
import $ from 'jquery'

const { roles, loadRoles } = getRoles()
const { updateRole } = putRole()
const role_id = ref(props.modelData.role_id)
const props = defineProps(['modelData'])
const emit = defineEmits({ closeModal: false })

const handleSubmit = () => {
    updateRole({
        id: props.modelData.id,
        role_id: role_id.value,
    }).then(() => {
        $('#editUserRole').modal('hide')
        emit('closeModal')
    }).catch()
}

onMounted(() => {
    loadRoles();
})

</script>


<style></style>
