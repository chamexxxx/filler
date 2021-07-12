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
                    :disabled="isLoading"
                >
                    <div v-if="isLoading" class="flex items-center">
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
import Description from "../components/Description.vue";

export default {
    name: "Start",
    components: { Description },
    data() {
        return {
            isLoading: false
        };
    },
    methods: {
        onStarted() {
            this.createGame(15, 15).then(response => {
                const { id } = response.data;
                this.$router.push({ name: "Game", params: { gameId: id } });
            });
        },
        createGame(width, height) {
            this.isLoading = true;

            return axios
                .post("api/game", { width, height })
                .finally(() => (this.isLoading = false));
        }
    }
};
</script>
