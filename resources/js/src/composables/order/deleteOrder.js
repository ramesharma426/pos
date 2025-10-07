import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const deleteOrder = () => {

  const errors = ref([]);

  const destroyOrder = async (order_id) => {
    await axiosInstance
      .delete("/order/delete/" + order_id)
      .then((res) => {
        showSuccessToast(res.data)
    })
    .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data);
        errors.value = err
        throw err
      });
  };

  return { errors, destroyOrder };
};

export default deleteOrder;
