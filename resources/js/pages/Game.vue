<template>
    <div class="d-flex flex-column justify-center items-center">
        <template v-if="!isLoading && rows.length > 0">
            <control-panel @selected="onSelected($event, 2)" />
            <field :rows="rows" class="p-5" />
            <control-panel @selected="onSelected($event, 1)" />
        </template>
        <span
            v-else
            class="spinner-grow spinner-grow-sm"
            role="status"
            aria-hidden="true"
        ></span>
    </div>
</template>

<script>
import ControlPanel from "../components/ControlPanel.vue";
import Field from "../components/Field.vue";

export default {
    name: "Game",
    components: { ControlPanel, Field },
    data() {
        return {
            gameData: null,
            isLoading: false
        };
    },
    methods: {
        onSelected(color, player) {},
        fetchGameData(id) {
            this.isLoading = true;

            axios
                .get(`api/game/${id}`)
                .then(response => {
                    this.gameData = response.data;
                })
                .finally((this.isLoading = false));
        }
    },
    computed: {
        rows() {
            if (!this.gameData) return [];

            const rows = [];

            const { field, cells } = this.gameData || {};
            const { width: fieldWidth, height: fieldHeight } = field;

            for (let row = 1; row <= fieldHeight; row++) {
                const width = row % 2 !== 0 ? fieldWidth : fieldWidth - 1;

                const fullRow = row - 1;
                const even = Math.floor(fullRow / 2);
                const uneven = fullRow - even;
                const fullCells = even * (fieldWidth - 1) + uneven * fieldWidth;

                rows[row - 1] = [];

                for (let column = 1; column <= width; column++) {
                    const index = fullCells + column - 1;
                    const cell = cells[index];

                    rows[row - 1].push(cell);
                }
            }

            return rows;
        }
    },
    created() {
        const { gameId } = this.$route.params;
        this.fetchGameData(gameId);
    }
};
</script>
