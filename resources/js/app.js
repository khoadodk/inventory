require("./bootstrap");

import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

// Routes
import { routes } from "./routes";
// Class
import User from "./helpers/User";
window.User = User;

const router = new VueRouter({
    routes,
    mode: "history"
});

const app = new Vue({
    router
}).$mount("#app");
