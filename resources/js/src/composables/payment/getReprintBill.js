import { ref } from "vue";
import { showErrorToast } from "../../toaster/toaster";
import axiosInstance from '../axios/axios'

const getReprintBill = () => {
  const billDetail = ref([]);
  const errors = ref([]);

  const loadBillDetail = async (order_id) => {

    await axiosInstance
      .get(`/payment/reprint/${order_id}`)
      .then((res) => {
        billDetail.value = res.data.data[0]
      })
      .catch((err) => {
        errors.value = err;
        if (err.response.status === 422) showErrorToast(err.response.data.errors);
        throw err
      });
  };

  return { errors, billDetail, loadBillDetail };
};

export default getReprintBill;
