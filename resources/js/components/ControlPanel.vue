<template>
    <div class="flex justify-center">
        <div class="flex justify-between">
            <cell
                v-for="color in colors"
                :key="color"
                :color="color"
                @click="onClick(color)"
            />
        </div>
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
