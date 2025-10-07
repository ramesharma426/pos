import { ref } from "vue";
import axiosInstance from "../axios/axios";
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const deleteUnit = () => {
  const errors = ref([]);

  const destroyUnit = async (unit_id) => {
    await axiosInstance
      .delete("/unit/delete/" + unit_id)
      .then((res) => {
        showSuccessToast({ message: res.data.message });
      })
      .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data.errors);
        errors.value = err;
        throw err;
      });
  };

  return { errors, destroyUnit };
};

export default deleteUnit;
