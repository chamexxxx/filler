<template>
    <div class="col-8">
        <div class="card text-center border-primary">
            <div class="card-body">
                <h4 class="card-title my-4 text-primary">
                    «Филлер» — игра для двух игроков
                </h4>
                <description />
                <button
                    @click="onStarted"
                    class="btn btn-primary btn-lg mt-4 mb-3"
                    type="button"
                    :disabled="loadingStatus"
                >
                    <div v-if="loadingStatus" class="flex items-center">
                        <span
                            class="spinner-grow spinner-grow-sm mr-2"
                            role="status"
                            aria-hidden="true"
                        ></span>
                        Идет создание игры...
                    </div>
                    <template v-else>Начать игру</template>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import Description from "../components/Description.vue";

export default {
    name: "Start",
    components: { Description },
    methods: {
        ...mapActions(['createGame']),
        onStarted() {
            this.createGame({ width: 15, height: 15 })
                .then(id => this.$router.push({ name: "Game", params: { gameId: id } }));
        },
    },
    computed: mapGetters(['loadingStatus'])
};
</script>
