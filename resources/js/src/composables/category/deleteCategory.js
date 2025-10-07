import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const deleteCategory = () => {

  const errors = ref([]);

  const destroyCategory = async (category_id) => {
    await axiosInstance
      .delete("/category/delete/" + category_id)
      .then((res) => {
        showSuccessToast({ message : res.data.message})
    })
    .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data.errors);
        errors.value = err
        throw err
      });
  };

  return { errors, destroyCategory };
};

export default deleteCategory;
