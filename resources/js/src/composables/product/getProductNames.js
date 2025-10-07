import { ref } from "vue";
import { showErrorToast } from "../../toaster/toaster";
import axiosInstance from '../axios/axios'

const getProductNames = () => {
  const productNames = ref([]);
  const errors = ref([]);

  const loadProductNames = async () => {

    await axiosInstance
      .get("/products/name")
      .then((res) => {
        productNames.value = res.data.data;
      })
      .catch((err) => {
        errors.value = err;
        if (err.response.status === 422) showErrorToast(err.response.data.errors);
        throw err
      });
  };

  return { productNames, errors, loadProductNames };
};

export default getProductNames;
