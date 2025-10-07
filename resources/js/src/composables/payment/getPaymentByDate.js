import { ref } from "vue";
import { showErrorToast } from "../../toaster/toaster";
import axiosInstance from '../axios/axios'

const getDiscountByDate = () => {
    const discountByDate = ref([]);
    const errors = ref([]);

    const loadDiscountByDate = async (queryObject = {}) => {

        let url  = "/payment/discount-by-date";
        if (Object.keys(queryObject).length !== 0) {
            const searchParams = new URLSearchParams(queryObject);
            const queryString = searchParams.toString();
            url = `${url}?${queryString}`;
        }

        await axiosInstance
            .get(url)
            .then((res) => {
                discountByDate.value = res.data.discount;
            })
            .catch((err) => {
                errors.value = err;
                if (err.response.status === 422)
                    showErrorToast(err.response.data.errors);
                throw err
            });
    };

    return { discountByDate, errors, loadDiscountByDate };
};

export default getDiscountByDate;
