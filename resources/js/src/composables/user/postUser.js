import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const postUser = () => {
  const errors = ref([]);
  const addUser = async (user) => {
    await axiosInstance
      .post("/user/store", user)
      .then((res) => {
        showSuccessToast({ message: res.data.message });
      })
      .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data.errors);
        errors.value = err;
        throw err;
      });
  };
  return { errors, addUser };
};

export default postUser;
