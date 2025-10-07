import { ref } from "vue";
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";
import axiosInstance from '../axios/axios'

const errors = ref([]);

const deliverOrderItem = async (orderItem_id) => {

    await axiosInstance
      .get(`/order-item-deliver/update/${orderItem_id}`)
      .then((res) => {
        showSuccessToast(res.data)
      })
      .catch((err) => {
        errors.value = err;
        if (err.response.status === 422) showErrorToast(err.response.data);
        throw err
      });
  };

export {errors, deliverOrderItem}
