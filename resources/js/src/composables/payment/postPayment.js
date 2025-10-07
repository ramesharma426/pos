import { ref } from "vue";
import axiosInstance from '../axios/axios'
import { showErrorToast, showSuccessToast } from "../../toaster/toaster";

const postPayment = () => {

  const errors = ref([]);

  let payment = ref([])

  const addPayment = async (bill_payment) => {

    await axiosInstance
      .post("/payment/store", bill_payment)
      .then((res) => {
        payment.value = res.data
        showSuccessToast({ message: res.data.message });
      })
      .catch((err) => {
        if (err.response.status === 422) showErrorToast(err.response.data);
        errors.value = err;
        throw err;
      });
  };

  return { errors, payment, addPayment };
};

export default postPayment;
