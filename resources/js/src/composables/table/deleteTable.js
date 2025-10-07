import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const deleteTable = () => {

  const errors = ref([]);

  const destroyTable = async (table_id) => {
    await axiosInstance
      .delete("/table/delete/" + table_id)
      .then((res) => {
        showSuccessToast({ message : res.data.message})
    })
    .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data.errors);
        errors.value = err
        throw err
      });
  };

  return { errors, destroyTable };
};

export default deleteTable;
