import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const putEmail = () => {
  const errors = ref([]);
  const updateEmail = async (Data) => {
    await axiosInstance
      .post("/user/email-update", Data)
      .then((res) => {
        showSuccessToast({ message: res.data.message });
      })
      .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data.errors);
        errors.value = err;
        throw err;
      });
  };
  return { errors, updateEmail };
};

export default putEmail;
