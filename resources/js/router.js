import Vue from "vue";
import VueRouter from "vue-router";

import Start from "./pages/Start.vue";
import Game from "./pages/Game.vue";

Vue.use(VueRouter);

const routes = [
    {
        path: "/",
        name: "Start",
        component: Start
    },
    {
        path: "/:gameId",
        name: "Game",
        component: Game
    }
];

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;
