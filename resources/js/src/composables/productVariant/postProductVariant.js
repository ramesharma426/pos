import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const postProductVariant = () => {

  const errors = ref([]);

  const addProductVariant = async (productVariant) => {

    const headers = { 'Content-Type': 'multipart/form-data' }
    await axiosInstance
      .post("/product-variant/store", productVariant, headers)
      .then((res) => {
        showSuccessToast({ message: res.data.message });
      })
      .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data);
        errors.value = err;
        throw err;
      });
  };

  return { errors, addProductVariant };
};

export default postProductVariant;
