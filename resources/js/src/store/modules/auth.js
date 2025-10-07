export default {
    namespaced: true,
    state() {
        return {
            authenticated: false,
            name: '',
            role: ''
        };
    },
    getters: {
        getAuthenticated(state) {
            if (!state.authenticated) {
                let browserLocalStorage = localStorage.getItem("UD");
                if (browserLocalStorage !== null)
                    return JSON.parse(browserLocalStorage).authenticated;
            } else return state.authenticated;
        },
        getName(state){
            if (!state.name) {
                let browserLocalStorage = localStorage.getItem("UD");
                if (browserLocalStorage !== null)
                    return JSON.parse(browserLocalStorage).name;
            } else return state.name;
        },
        getRole(state){
            if (!state.role) {
                let browserLocalStorage = localStorage.getItem("UD");
                if (browserLocalStorage !== null)
                    return JSON.parse(browserLocalStorage).role;
            } else return state.role;
        }
    },
    mutations: {
        saveCredentials(state, payload) {
            state.authenticated = payload.authenticated;
            state.name = payload.name;
            state.role = payload.role;
            localStorage.setItem("UD", JSON.stringify(payload));
        },
        logout(state) {
            state.authenticated = false;
            state.name = ''
            state.role = ''
            if (localStorage.getItem("UD") !== null) localStorage.removeItem("UD");
        },
    },
    actions: {},
};
