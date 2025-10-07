import {createRouter, createWebHistory} from "vue-router";
import store from "../store/store";

const routes = [
    {
        path: "/login",
        name: "login",
        component: () => import("../views/pages/login/Login.vue"),
        meta: {role: 'admin'}
    },
    {
        path: "/",
        name: "dashboard",
        //component: () => import("../views/pages/Dashboard.vue"),
        redirect: to => {
            return {name: 'orders'}
        },
    },
    {
        path: "/users",
        name: "users",
        component: () => import("../views/pages/user/Users.vue"),
    },
    {
        path: "/units",
        name: "units",
        component: () => import("../views/pages/unit/Units.vue"),
    },
    {
        path: "/categories",
        name: "categories",
        component: () => import("../views/pages/category/Categories.vue"),
    },
    {
        path: "/products",
        name: "products",
        component: () => import("../views/pages/product/Products.vue"),
    },
    {
        path: "/tables",
        name: "tables",
        component: () => import("../views/pages/table/Tables.vue"),
    },
    {
        path: "/orders",
        name: "orders",
        component: () => import("../views/pages/order/Orders.vue"),
    },
    {
        path: "/product-variants",
        name: "productVariants",
        component: () => import("../views/pages/productVariant/ProductVariant.vue"),
    },
    {
        path: "/sales",
        name: "sales",
        component: () => import("../views/pages/sales/Sales.vue"),
    },
    {
        path: "/purchases",
        name: "purchases",
        component: () => import("../views/pages/purchase/Purchases.vue"),
    },
    {
        name: "notFound",
        path: "/:pathMatch(.*)*",
        component: () => import("../views/pages/notFound/NotFound.vue"),
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

const checkAuth = () => {
    return store.getters["auth/getAuthenticated"]
};

router.beforeEach((to, from) => {
    if (!checkAuth() && to.name !== "login") return {name: "login"};
    if (checkAuth() && to.name === "login") return from;
    if (checkAuth() && to.name !== "login") return true;
});

export default router;
