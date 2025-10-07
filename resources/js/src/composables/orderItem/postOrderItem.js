import { ref } from "vue";
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";
import axiosInstance from '../axios/axios'

const postOrderItem = () => {

  const errors = ref([]);

  const addOrderItem = async (orderItem) => {

    await axiosInstance
      .post("/order-item/store", orderItem)
      .then((res) => {
        showSuccessToast({ message: res.data.message });
      })
      .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data);
        errors.value = err;
        throw err;
      });
  };

  return { errors, addOrderItem };
};

export default postOrderItem;
