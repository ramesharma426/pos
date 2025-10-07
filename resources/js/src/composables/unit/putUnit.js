import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const putUnit = () => {
  const errors = ref([]);
  const updateUnit = async (unit) => {
    await axiosInstance
      .post("/unit/update", unit)
      .then((res) => {
        showSuccessToast({ message: res.data.message });
      })
      .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data);
        errors.value = err;
        throw err;
      });
  };
  return { errors, updateUnit };
};
export default putUnit;
