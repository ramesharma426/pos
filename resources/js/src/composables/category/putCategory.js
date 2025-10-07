import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const putCategory = () => {

  const errors = ref([]);

  const updateCategory = async (category) => {
    await axiosInstance
      .post("/category/update", category)
      .then((res) => {
        showSuccessToast({ message: res.data.message });
      })
      .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data);
        errors.value = err;
        throw err;
      });
  };

  return { errors, updateCategory };
};

export default putCategory;
