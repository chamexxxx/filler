<template>
    <div class="d-flex flex-column justify-content-center align-items-center min-vh-100 min-vw-100 bg-dark-image">
        <div>
            <template v-if="!isLoading && rows.length > 0">
                <div class="d-flex justify-content-between align-items-center px-3">
                    <span class="mr-3 lead text-white">0%</span>
                    <control-panel class="flex-grow-1" @selected="onSelected($event, 2)" />
                </div>

                <field :rows="rows" />

                <div class="d-flex justify-content-between align-items-center px-3">
                    <control-panel class="flex-grow-1" @selected="onSelected($event, 1)" />
                    <span class="lead text-white ml-3">0%</span>
                </div>
            </template>
            <span
                v-else
                class="spinner-grow spinner-grow-sm"
                role="status"
                aria-hidden="true"
            ></span>
        </div>
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
        onSelected(color, playerId) {
            this.updateGame(color, playerId).then(this.fetchGameData);
        },
        updateGame(color, playerId) {
            return axios.patch(`api/game/${this.gameId}`, { color, playerId });
        },
        fetchGameData() {
            this.isLoading = true;

            axios
                .get(`api/game/${this.gameId}`)
                .then(response => {
                    this.gameData = response.data;
                })
                .finally((this.isLoading = false));
        }
    },
    computed: {
        gameId() {
            return this.$route.params.gameId;
        },
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
        this.fetchGameData();
    }
};
</script>
