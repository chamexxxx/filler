import Vue from "vue";
import VueRouter from "vue-router";

import Start from "./pages/Start.vue";
import Game from "./pages/Game.vue";

Vue.use(VueRouter);

const routes = [];

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;
