import { ref } from "vue";
import { showErrorToast } from "../../toaster/toaster";
import axiosInstance from "../axios/axios";

const getSales = () => {
    const sales = ref([]);
    const errors = ref([]);

    const loadSales = async (queryObject = {}) => {
        let url = "/sales";
        if (Object.keys(queryObject).length !== 0) {
            const searchParams = new URLSearchParams(queryObject);
            const queryString = searchParams.toString();
            url = `${url}?${queryString}`;
        }

        await axiosInstance
            .get(url)
            .then((res) => {
                sales.value = res.data.data;
            })
            .catch((err) => {
                errors.value = err;
                if (err.response.status === 422) showErrorToast(err.response.data.errors);
                throw err
            });
    };

    return { sales, errors, loadSales };
};

export default getSales;
