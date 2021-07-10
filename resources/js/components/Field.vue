<template>
    <div>
        <row
            v-for="(row, index) in rows"
            :key="index"
            :cells="row"
            :style="
                (index + 1) % 2 === 0 &&
                    'margin-left: 21px; margin-top: -22px; margin-bottom: -22px'
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
