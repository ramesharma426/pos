import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const putTable = () => {
  const errors = ref([]);
  const updateTable = async (table) => {
    await axiosInstance
      .post("/table/update", table)
      .then((res) => {
        showSuccessToast({ message: res.data.message });
      })
      .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data);
        errors.value = err;
        throw err;
      });
  };
  return { errors, updateTable };
};
export default putTable;
