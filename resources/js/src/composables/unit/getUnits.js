import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast } from "../../toaster/toaster";

const getUnits = () => {
  const units = ref([]);
  const errors = ref([]);

  const loadUnits = async () => {
    await axiosInstance
      .get("/units")
      .then((res) => (units.value = res.data.data))
      .catch((err) => {
        errors.value = err
        if (err.response.status === 422) showErrorToast(err.response.data.errors);
        throw err
      });
  };

  return { units, errors, loadUnits };
};

export default getUnits;
