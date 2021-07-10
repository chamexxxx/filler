<template>
    <div>
        <cell v-for="(cell, index) in cells" :key="index" v-bind="cell" />
    </div>
</template>

<script>
import Cell from "./Cell.vue";

export default {
    name: "Row",
    components: { Cell },
    props: {
        cells: {
            type: Array,
            required: true
        }
    }
};
</script>
