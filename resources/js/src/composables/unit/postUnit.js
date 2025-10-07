import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const postUnit = () => {

  const errors = ref([]);

  const addUnit = async (unit) => {

    await axiosInstance
      .post("/unit/store", unit)
      .then((res) => {
        showSuccessToast({ message: res.data.message });
      })
      .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data);
        errors.value = err;
        throw err;
      });
  };

  return { errors, addUnit };
};

export default postUnit;
