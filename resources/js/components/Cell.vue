<template>
    <div
        :class="`cell cell--${color}`"
        v-on="$listeners"
        :style="$listeners.click && 'cursor: pointer'"
    ></div>
</template>

<script>
import { COLORS } from "../constants";

export default {
    name: "Cell",
    props: {
        color: {
            type: String,
            required: true,
            validator(val) {
                return COLORS.includes(val);
            }
        }
    },
};
</script>

<style lang="sass">
$cell-size: 15px

.cell
    display: inline-block
    width: 0
    height: 0
    margin: 3px 6px
    transform: rotate(45deg)
    &--blue
        border-left: $cell-size solid #5761ff
        border-top: $cell-size solid #2c36dc
        border-right: $cell-size solid #05085b
        border-bottom: $cell-size solid #000ab6
    &--green
        border-left: $cell-size solid #4ce44e
        border-top: $cell-size solid #1db822
        border-right: $cell-size solid #004400
        border-bottom: $cell-size solid #009204
    &--cyan
        border-left: $cell-size solid #46ebff
        border-top: $cell-size solid #1ecbde
        border-right: $cell-size solid #07565e
        border-bottom: $cell-size solid #009fb5
    &--red
        border-left: $cell-size solid #ff6357
        border-top: $cell-size solid #e03425
        border-right: $cell-size solid #5a0704
        border-bottom: $cell-size solid #b50f00
    &--magenta
        border-left: $cell-size solid #ff6fff
        border-top: $cell-size solid #e947da
        border-right: $cell-size solid #692473
        border-bottom: $cell-size solid #bc1bb7
    &--yellow
        border-left: $cell-size solid #fff34b
        border-top: $cell-size solid #ffcc16
        border-right: $cell-size solid #99621d
        border-bottom: $cell-size solid #ffa600
    &--white
        border-left: $cell-size solid #feffff
        border-top: $cell-size solid #cfd0d2
        border-right: $cell-size solid #46494e
        border-bottom: $cell-size solid #aeabad
</style>
