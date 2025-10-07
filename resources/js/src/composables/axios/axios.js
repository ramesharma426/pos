import axios from "axios";
import {
    ProgressFinisher,
    useProgress,
} from "@marcoschulte/vue3-progress";
import store from "../../store/store";
import { showErrorToast } from "../../toaster/toaster";

const axiosInstance = axios.create({
    baseURL: import.meta.env.VITE_MIX_APP_URL,
});

let progresses = [];
axiosInstance.defaults.headers.common["Accept"] = "application/json";
axiosInstance.interceptors.request.use((config) => {
    //if (config.url !== "/login") config.headers.withCredentials = true;

    config.headers.withCredentials = true;

    progresses.push(useProgress().start());
    return config;
});

axiosInstance.interceptors.response.use(
    (response) => {
        progresses.pop()?.finish();
        return response;
    },
    (error) => {
        progresses.pop()?.finish();

        if(error.response.status === 401){
            showErrorToast({message: "Session Expired"})
            store.commit("auth/logout");
            window.location.href = "/login";
        }

        return Promise.reject(error);
    }
);

export default axiosInstance;
