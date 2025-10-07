import { ref } from "vue";
import { showErrorToast } from "../../toaster/toaster";
import axiosInstance from '../axios/axios'

const getProductVariants = () => {
  const productVariants = ref([]);
  const meta = ref([]);
  const errors = ref([]);

  const loadProductVariants = async (queryObject = {}) => {

    let url  = "/product-variants";
    if (Object.keys(queryObject).length !== 0) {
      const searchParams = new URLSearchParams(queryObject);
      const queryString = searchParams.toString();
      url = `${url}?${queryString}`;
    }

    await axiosInstance
      .get(url)
      .then((res) => {
        productVariants.value = res.data.data;
        meta.value = res.data.meta;
      })
      .catch((err) => {
        errors.value = err;
        if (err.response.status === 422)
          showErrorToast(err.response.data.errors);
        throw err
      });
  };

  return { meta, productVariants, errors, loadProductVariants };
};

export default getProductVariants;
