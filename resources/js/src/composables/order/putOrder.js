import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const putOrder = () => {
  const errors = ref([]);

  const updateOrder = async (order) => {

    await axiosInstance
      .post("/order/update", order)
      .then((res) => {
        showSuccessToast(res.data);
      })
      .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data);
        errors.value = err;
        throw err;
      });
  };

  return { errors, updateOrder };
};

export default putOrder;
