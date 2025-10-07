import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const postPurchase = () => {
    const errors = ref([]);
    const addPurchase = async (purchase) => {
        await axiosInstance
            .post("/purchase/store", purchase)
            .then((res) => {
                showSuccessToast({ message: res.data.message });
            })
            .catch((err) => {
                if (err.response.status === 422) showErrorToast(err.response.data.errors);
                errors.value = err;
                throw err;
            });
    };
    return { errors, addPurchase };
};

export default postPurchase;
