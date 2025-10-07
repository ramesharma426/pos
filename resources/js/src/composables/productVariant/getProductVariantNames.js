import { ref } from "vue";
import { showErrorToast } from "../../toaster/toaster";
import axiosInstance from '../axios/axios'

const getProductVariantNames = () => {
  const productVariantNames = ref([]);
  const errors = ref([]);

  const loadProductVariantNames = async () => {

    await axiosInstance
      .get("/product-variants/name")
      .then((res) => {
        productVariantNames.value = res.data.data;
      })
      .catch((err) => {
        errors.value = err;
        if (err.response.status === 422) showErrorToast(err.response.data.errors);
        throw err
      });
  };

  return { productVariantNames, errors, loadProductVariantNames };
};

export default getProductVariantNames;
