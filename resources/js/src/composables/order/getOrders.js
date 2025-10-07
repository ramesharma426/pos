import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast } from "../../toaster/toaster";

const getProducts = () => {
  const orders = ref([]);
  const meta = ref([]);
  const errors = ref([]);

  const loadOrders = async (queryObject = {}) => {

    let url  = "/orders";
    if (Object.keys(queryObject).length !== 0) {
      const searchParams = new URLSearchParams(queryObject);
      const queryString = searchParams.toString();
      url = `${url}?${queryString}`;
    }

    await axiosInstance
      .get(url)
      .then((res) => {
        orders.value = res.data.data;
        meta.value = res.data.meta;
      })
      .catch((err) => {
        errors.value = err;
        if (err.response.status === 422) showErrorToast(err.response.data);
      });
  };

  return { meta, orders, errors, loadOrders };
};

export default getProducts;
