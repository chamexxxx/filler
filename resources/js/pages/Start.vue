<template>
    <div>
        <button @click="onStarted">Start the game</button>
    </div>
</template>

<script>
export default {
    name: "Start",
    methods: {
        onStarted() {
            this.$router.push({ name: "Game" });
        }
    }
};
</script>
