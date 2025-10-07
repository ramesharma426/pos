import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const putPassword = () => {
  const errors = ref([]);
  const updatePassword = async (Data) => {
    await axiosInstance
      .post("/user/password-update", Data)
      .then((res) => {
        showSuccessToast({ message: res.data.message });
      })
      .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data.errors);
        errors.value = err;
        throw err;
      });
  };
  return { errors, updatePassword };
};

export default putPassword;
