import { ref } from "vue";
import { showErrorToast } from "../../toaster/toaster";
import axiosInstance from '../axios/axios'

const getOrderItems = () => {
  const orderItems = ref([]);
  const errors = ref([]);

  const loadOrderItems = async (order_id) => {

    await axiosInstance
      .get(`/order-items/${order_id}`)
      .then((res) => {
        orderItems.value = res.data.data;
      })
      .catch((err) => {
        errors.value = err;
        if (err.response.status === 422) showErrorToast(err.response.data);
        throw err
      });
  };

  return { orderItems, errors, loadOrderItems };
};

export default getOrderItems;
