import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast } from "../../toaster/toaster";

const getCategories = () => {
  const categories = ref([]);
  const errors = ref([]);

  const loadCategories = async () => {
    await axiosInstance
      .get("/categories")
      .then((res) => (categories.value = res.data.data))
      .catch((err) => {
        errors.value = err
        if (err.response.status === 422) showErrorToast(err.response.data.errors);
        throw err
      });
  };

  return { categories, errors, loadCategories };
};

export default getCategories;
