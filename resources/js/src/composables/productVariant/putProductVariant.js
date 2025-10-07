import { ref } from "vue";
import axiosInstance from "../axios/axios";
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const putProductVariant = () => {
  const errors = ref([]);
  const updateProductVariant = async (productVariant) => {
    const headers = { "Content-Type": "multipart/form-data" };

    await axiosInstance
      .post("/product-variant/update", productVariant, headers)
      .then((res) => {
        showSuccessToast({ message: res.data.message });
      })
      .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data);
        errors.value = err;
        throw err;
      });
  };
  return { errors, updateProductVariant };
};
export default putProductVariant;
