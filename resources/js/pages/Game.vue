<template>
    <div class="position-relative d-flex flex-column justify-content-center align-items-center min-vh-100 min-vw-100 bg-dark-image">
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
        <div :class="`bottom-left-triangle bottom-left-triangle--${getPlayerColor(1)}`"></div>
        <div :class="`top-right-triangle top-right-triangle--${getPlayerColor(2)}`"></div>
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
            return axios.patch(`api/game/${this.gameId}`, { color, playerId })
        },
        fetchGameData() {
            this.isLoading = true;

            axios
                .get(`api/game/${this.gameId}`)
                .then(response => {
                    this.gameData = response.data;
                })
                .finally(() => this.isLoading = false);
        },
        getPlayerColor(playerId) {
          return this.gameData ? this.gameData.players.find(({ id }) => id === playerId).color : null;
        },
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
        },
    },
    created() {
        this.fetchGameData();
    }
};
</script>

<style lang="sass">

$blue: #3f47bf
$blue-border: #0207a0
$green: #49e553
$green-border: #009101
$cyan: #46eeff
$cyan-border: #0097ab
$red: #be4b3f
$red-border: #990f00
$magenta: #be52be
$magenta-border: #9e1296
$yellow: #fff44e
$yellow-border: #ffa800
$white: #ffffff
$white-border: #aeaaac

.top-right-triangle,
.bottom-left-triangle
    position: fixed
    width: 0
    height: 0

.bottom-left-triangle
    bottom: -65px
    left: -15px
    border-top: 100px solid transparent
    border-bottom: 100px solid transparent
    transform: rotate(-45deg)
    &::before
        content: ""
        position: fixed
        bottom: -90px
        left: 5px
        width: 0
        height: 0
        border-top: 90px solid transparent
        border-bottom: 90px solid transparent
    &--blue
        border-right: 100px solid $blue-border
        &::before
            border-right: 90px solid $blue
    &--green
        border-right: 100px solid $green-border
        &::before
            border-right: 90px solid $green
    &--cyan
        border-right: 100px solid $cyan-border
        &::before
            border-right: 90px solid $cyan
    &--red
        border-right: 100px solid $red-border
        &::before
            border-right: 90px solid $red
    &--magenta
        border-right: 100px solid $magenta-border
        &::before
            border-right: 90px solid $magenta
    &--yellow
        border-right: 100px solid $yellow-border
        &::before
            border-right: 90px solid $yellow
    &--white
        border-right: 100px solid $white-border
        &::before
            border-right: 90px solid $white

.top-right-triangle
    top: -65px
    right: -15px
    border-top: 100px solid transparent
    border-bottom: 100px solid transparent
    transform: rotate(-45deg)
    &::before
        content: ""
        position: fixed
        top: -90px
        right: 5px
        width: 0
        height: 0
        border-top: 90px solid transparent
        border-bottom: 90px solid transparent
    &--blue
        border-left: 100px solid $blue-border
        &::before
            border-left: 90px solid $blue
    &--green
        border-left: 100px solid $green-border
        &::before
            border-left: 90px solid $green
    &--cyan
        border-left: 100px solid $cyan-border
        &::before
            border-left: 90px solid $cyan
    &--red
        border-left: 100px solid $red-border
        &::before
            border-left: 90px solid $red
    &--magenta
        border-left: 100px solid $magenta-border
        &::before
            border-left: 90px solid $magenta
    &--yellow
        border-left: 100px solid $yellow-border
        &::before
            border-left: 90px solid $yellow
    &--white
        border-left: 100px solid $white-border
        &::before
            border-left: 90px solid $white

</style>
