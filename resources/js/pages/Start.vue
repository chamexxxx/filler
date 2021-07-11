<template>
    <div class="col-8">
        <div class="card text-center border-primary">
            <div class="card-body">
                <h4 class="card-title my-4 text-primary">
                    «Филлер» — игра для двух игроков
                </h4>
                <description />
                <button @click="onStarted" class="btn btn-primary btn-lg mt-4 mb-3">
                    Начать игру
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
    methods: {
        onStarted() {
            this.$router.push({ name: "Game" });
        }
    }
};
</script>
