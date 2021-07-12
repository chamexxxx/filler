<template>
    <div>
        <row
            v-for="(row, index) in rows"
            :key="index"
            :cells="row"
            style="margin: 6px"
            :style="
                (index + 1) % 2 === 0 &&
                    'margin-left: 27px; margin-top: -21px; margin-bottom: -21px;'
            "
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
