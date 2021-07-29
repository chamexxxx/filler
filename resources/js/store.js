import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        game: {
            id: null,
            field: null,
            players: null,
            currentPlayerId: null,
            winnerPlayerId: null,
        },
        loading: false,
    },
    mutations: {
        setGame(state, payload) {
            state.game = payload;
        },
        changeLoadingStatus(state, status) {
            state.loading = !!status;
        },
    },
    getters: {
        game(state) {
            return state.game;
        },
        loadingStatus(state) {
            return state.loading;
        },
    },
    actions: {
        createGame({ commit }, { width, height }) {
            commit('changeLoadingStatus', true);
            return axios
                .post("api/game", { width, height })
                .then(response => response.data.id)
                .finally(() => commit('changeLoadingStatus', false));
        },
        updateGame({ commit }, { color, playerId, id, loading = true }) {
            commit('changeLoadingStatus', loading);

            return axios
                .patch(`api/game/${id}`, { color, playerId })
                .finally(() => commit('changeLoadingStatus', false));
        },
        fetchGame({ commit }, { id, loading = true }) {
            commit('changeLoadingStatus', loading);

            return axios
                .get(`api/game/${id}`)
                .then(response => commit('setGame', response.data))
                .finally(() => commit('changeLoadingStatus', false));
        },
    },
});

export default store;
