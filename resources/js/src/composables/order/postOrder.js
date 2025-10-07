import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const postOrder = () => {
  const errors = ref([]);

  const addOrder = async (order) => {

    await axiosInstance
      .post("/order/store", order)
      .then((res) => {
        showSuccessToast(res.data);
      })
      .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data);
        errors.value = err;
        throw err;
      });
  };

  return { errors, addOrder };
};

export default postOrder;
