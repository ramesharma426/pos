<template>
    <Teleport to=".teleport-modal">
        <div class="modal fade show" data-backdrop="static" id="addCategory" tabindex="-1" role="dialog"
            aria-labelledby="example1" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Add Category </h4>
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
                                    <input type="text" class="form-control" placeholder="Category Name" v-model="name"
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
import postCategory from '../../../../composables/category/postCategory';
import $ from 'jquery'
const { addCategory } = postCategory()
const emit = defineEmits({ closeModal: false })
const name = ref('')

const handleSubmit = () => {
    addCategory({name: name.value}).then(() => {
        $('#addCategory').modal('hide')
        emit('closeModal')
    }).catch()
}

</script>


<style></style>
