// UI auth state ("UD") is persisted in a cookie instead of localStorage so it
// survives full page reloads. NOTE: this is only UI state (authenticated flag,
// display name, role) — it is NOT the security boundary. Real authentication is
// the Sanctum httpOnly session cookie set by the server.
const UD_COOKIE = "UD";
const UD_MAX_DAYS = 7;

function setCookie(name, value, days = UD_MAX_DAYS) {
    const expires = new Date(Date.now() + days * 864e5).toUTCString();
    document.cookie = `${name}=${encodeURIComponent(value)}; expires=${expires}; path=/; SameSite=Lax`;
}

function getCookie(name) {
    const match = document.cookie.match('(?:^|; )' + name + '=([^;]*)');
    return match ? decodeURIComponent(match[1]) : null;
}

function eraseCookie(name) {
    document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/; SameSite=Lax`;
}

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
                let stored = getCookie(UD_COOKIE);
                if (stored !== null)
                    return JSON.parse(stored).authenticated;
            } else return state.authenticated;
        },
        getName(state){
            if (!state.name) {
                let stored = getCookie(UD_COOKIE);
                if (stored !== null)
                    return JSON.parse(stored).name;
            } else return state.name;
        },
        getRole(state){
            if (!state.role) {
                let stored = getCookie(UD_COOKIE);
                if (stored !== null)
                    return JSON.parse(stored).role;
            } else return state.role;
        }
    },
    mutations: {
        saveCredentials(state, payload) {
            state.authenticated = payload.authenticated;
            state.name = payload.name;
            state.role = payload.role;
            setCookie(UD_COOKIE, JSON.stringify(payload));
        },
        logout(state) {
            state.authenticated = false;
            state.name = ''
            state.role = ''
            if (getCookie(UD_COOKIE) !== null) eraseCookie(UD_COOKIE);
        },
    },
    actions: {},
};
