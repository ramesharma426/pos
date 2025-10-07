import {ref} from "vue";
import axiosInstance from '../axios/axios'
import {showErrorToast} from "../../toaster/toaster";
import {useStore} from "vuex";

const postLogin = () => {
    const store = useStore();
    const errors = ref([]);

    const login = async (credentials) => {
        await axiosInstance.get("/sanctum/csrf-cookie")
        await axiosInstance
            .post("/login", credentials)
            .then((res) => {
                store.commit("auth/saveCredentials", {
                    authenticated: true,
                    name: res.data.name,
                    role: res.data.role
                });
            })
            .catch((err) => {
                if (err.response.status === 422) showErrorToast(err.response.data);
                errors.value = err;
                throw err;
            });
    };

    return {errors, login};
};

export default postLogin;
