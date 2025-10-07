import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast } from "../../toaster/toaster";

const getTables = () => {
  const tables = ref([]);
  const meta = ref([]);
  const errors = ref([]);

  const loadTables = async () => {

    let url  = "/tables";

    await axiosInstance
      .get(url)
      .then((res) => {
        tables.value = res.data.data;
        meta.value = res.data.meta;
      })
      .catch((err) => {
        errors.value = err;
        if (err.response.status === 422) showErrorToast(err.response.data.errors);
        throw err
      });
  };

  return { tables, errors, loadTables };
};

export default getTables;
