import { ref } from "vue"
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";
import { useStore } from "vuex";
import axiosInstance from "../axios/axios";

const getLogout = () => {
  const store = useStore();
  const errors = ref([]);

  const logout = async () => {
    await axiosInstance
      .get("/logout")
      .then( (res) => {
        store.commit('auth/logout')
        showSuccessToast(res.data);
      })
      .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data);
        errors.value = err;
        throw err;
      });
  };

  return { errors, logout };
};

export default getLogout;
