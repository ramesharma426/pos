import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const postCategory = () => {

  const errors = ref([]);

  const addCategory = async (category) => {
    await axiosInstance
      .post("/category/store", category)
      .then((res) => {
        showSuccessToast({ message: res.data.message });
      })
      .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data);
        errors.value = err;
        throw err;
      });
  };

  return { errors, addCategory };
};

export default postCategory;
