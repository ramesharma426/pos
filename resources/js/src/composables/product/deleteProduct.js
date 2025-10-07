import { ref } from "vue";
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";
import axiosInstance from '../axios/axios'

const deleteProduct = () => {

  const errors = ref([]);

  const destroyProduct = async (product_id) => {
    await axiosInstance
      .delete("/product/delete/" + product_id)
      .then((res) => {
        showSuccessToast(res.data)
    })
    .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data);
        errors.value = err
        throw err
      });
  };

  return { errors, destroyProduct };
};

export default deleteProduct;
