import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const putProductStock = () => {
  const errors = ref([]);
  const updateProductStock = async (stock) => {

    await axiosInstance
      .post("/product/stock/update", stock)
      .then((res) => {
        showSuccessToast({ message: res.data.message });
      })
      .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data);
        errors.value = err;
        throw err;
      });
  };
  return { errors, updateProductStock };
};
export default putProductStock;
