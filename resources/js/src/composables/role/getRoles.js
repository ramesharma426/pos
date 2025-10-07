import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast } from "../../toaster/toaster";

const getRoles = () => {
  const roles = ref([]);
  const errors = ref([]);

  const loadRoles = async () => {
    await axiosInstance
      .get("/roles")
      .then((res) => (roles.value = res.data.data))
      .catch((err) => {
        errors.value = err
        if (err.response.status === 422) showErrorToast(err.response.data.errors);
        throw err
      });
  };

  return { roles, errors, loadRoles };
};

export default getRoles;
