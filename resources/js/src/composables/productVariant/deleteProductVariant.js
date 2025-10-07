import { ref } from "vue";
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";
import axiosInstance from '../axios/axios'

const deleteProductVariant = () => {

  const errors = ref([]);

  const destroyProductVariant = async (product_id) => {
    await axiosInstance
      .delete("/product-variant/delete/" + product_id)
      .then((res) => {
        showSuccessToast(res.data)
    })
    .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data);
        errors.value = err
        throw err
      });
  };

  return { errors, destroyProductVariant };
};

export default deleteProductVariant;
