import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const errors = ref([]);

const cancelOrderItem = async (orderItem_id) => {

    await axiosInstance
      .get(`/order-item-cancel/${orderItem_id}`)
      .then((res) => {
        showSuccessToast(res.data)
      })
      .catch((err) => {
        errors.value = err;
        if (err.response.status === 422) showErrorToast(err.response.data);
        throw err
      });
  };

export {errors, cancelOrderItem}
