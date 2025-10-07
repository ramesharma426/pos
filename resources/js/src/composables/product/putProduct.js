import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const putProduct = () => {
  const errors = ref([]);
  const updateProduct = async (product) => {

    await axiosInstance
      .post("/product/update", product)
      .then((res) => {
        showSuccessToast({ message: res.data.message });
      })
      .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data);
        errors.value = err;
        throw err;
      });
  };
  return { errors, updateProduct };
};
export default putProduct;
