<template>
    <div class="position-relative my-5 p-3" style="background: #121212; border: 3px solid white; border-radius: 7px;">
        <template v-if="$slots.default">
            <div class="d-flex flex-column justify-content-center align-items-center bg-blackout">
                <slot></slot>
            </div>
        </template>
        <row
            v-for="(row, index) in rows"
            :key="index"
            :cells="row"
            class="row"
            :class="{ 'even-row': (index + 1) % 2 === 0 }"
        />
    </div>
</template>

<script>
import Row from "./Row.vue";

export default {
    name: "Field",
    components: { Row },
    props: {
        rows: {
            type: Array,
            required: true,
            validator(rows) {
                return rows.every(item => item && Array.isArray(item));
            }
        }
    }
};
</script>

<style lang="sass">
.row
    margin: 6px

.even-row
    margin-left: 27px
    margin-top: -21px
    margin-bottom: -21px
</style>
