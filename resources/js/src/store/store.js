import { createStore } from "vuex";
import auth from "./modules/auth";

const storeInstance = createStore({
  modules: {
    auth: auth,
  },
});

export default storeInstance;
