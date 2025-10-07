import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const postProduct = () => {

  const errors = ref([]);

  const addProduct = async (product) => {

    await axiosInstance
      .post("/product/store", product)
      .then((res) => {
        showSuccessToast({ message: res.data.message });
      })
      .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data);
        errors.value = err;
        throw err;
      });
  };

  return { errors, addProduct };
};

export default postProduct;
