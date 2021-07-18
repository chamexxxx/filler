<template>
    <div class="d-flex justify-content-around">
        <cell
            v-for="color in colors"
            :key="color"
            :color="color"
            @click="onClick(color)"
        />
    </div>
</template>

<script>
import { COLORS } from "../constants";
import Cell from "./Cell.vue";

export default {
    name: "ControlPanel",
    components: { Cell },
    data() {
        return {
            colors: COLORS
        };
    },
    methods: {
        onClick(color) {
            this.$emit("selected", color);
        }
    }
};
</script>
