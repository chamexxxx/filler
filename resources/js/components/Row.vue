<template>
    <div class="d-flex">
        <cell
            v-for="(cell, index) in cells"
            :key="index"
            v-bind="cell"
            :rowNumber="number"
            :number="index + 1"
            :rowLast="last"
            :last="index === cells.length - 1"
        />
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
        },
        number: Number,
        last: Boolean
    }
};
</script>
