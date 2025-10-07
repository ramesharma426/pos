import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const putName = () => {
  const errors = ref([]);
  const updateName = async (Data) => {
    await axiosInstance
      .post("/user/name-update", Data)
      .then((res) => {
        showSuccessToast({ message: res.data.message });
      })
      .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data.errors);
        errors.value = err;
        throw err;
      });
  };
  return { errors, updateName };
};

export default putName;
