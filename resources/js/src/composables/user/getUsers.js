import { ref } from "vue";
import { showErrorToast } from "../../toaster/toaster";
import axiosInstance from "../axios/axios";

const getUsers = () => {
  const users = ref([]);
  const meta = ref([]);
  const errors = ref([]);

  const loadUsers = async (queryObject = {}) => {
    let url = "/users";
    if (Object.keys(queryObject).length !== 0) {
      const searchParams = new URLSearchParams(queryObject);
      const queryString = searchParams.toString();
      url = `${url}?${queryString}`;
    }

    await axiosInstance
      .get(url)
      .then((res) => {
        users.value = res.data.data;
        meta.value = res.data.meta;
      })
      .catch((err) => {
        errors.value = err;
        if (err.response.status === 422) showErrorToast(err.response.data.errors);
        throw err
      });
  };

  return { meta, users, errors, loadUsers };
};

export default getUsers;
