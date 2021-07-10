<template>
    <div class="d-flex flex-column justify-center items-center">
        <control-panel @selected="onSelected($event, 2)" />
        <field :rows="rows" class="p-5" />
        <control-panel @selected="onSelected($event, 1)" />
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
            rows: [
                [
                    {
                        color: "blue",
                        playerId: 1
                    },
                    {
                        color: "green",
                        playerId: 1
                    },
                    {
                        color: "cyan",
                        playerId: 1
                    },
                    {
                        color: "red",
                        playerId: 1
                    },
                    {
                        color: "magenta",
                        playerId: 1
                    },
                    {
                        color: "yellow",
                        playerId: 1
                    },
                    {
                        color: "white",
                        playerId: 1
                    },
                    {
                        color: "blue",
                        playerId: 1
                    }
                ],
                [
                    {
                        color: "blue",
                        playerId: 1
                    },
                    {
                        color: "green",
                        playerId: 1
                    },
                    {
                        color: "cyan",
                        playerId: 1
                    },
                    {
                        color: "red",
                        playerId: 1
                    },
                    {
                        color: "magenta",
                        playerId: 1
                    },
                    {
                        color: "yellow",
                        playerId: 1
                    },
                    {
                        color: "white",
                        playerId: 1
                    }
                ],
                [
                    {
                        color: "blue",
                        playerId: 1
                    },
                    {
                        color: "green",
                        playerId: 1
                    },
                    {
                        color: "cyan",
                        playerId: 1
                    },
                    {
                        color: "red",
                        playerId: 1
                    },
                    {
                        color: "magenta",
                        playerId: 1
                    },
                    {
                        color: "yellow",
                        playerId: 1
                    },
                    {
                        color: "white",
                        playerId: 1
                    },
                    {
                        color: "blue",
                        playerId: 1
                    }
                ],
                [
                    {
                        color: "blue",
                        playerId: 1
                    },
                    {
                        color: "green",
                        playerId: 1
                    },
                    {
                        color: "cyan",
                        playerId: 1
                    },
                    {
                        color: "red",
                        playerId: 1
                    },
                    {
                        color: "magenta",
                        playerId: 1
                    },
                    {
                        color: "yellow",
                        playerId: 1
                    },
                    {
                        color: "white",
                        playerId: 1
                    }
                ],
                [
                    {
                        color: "blue",
                        playerId: 1
                    },
                    {
                        color: "green",
                        playerId: 1
                    },
                    {
                        color: "cyan",
                        playerId: 1
                    },
                    {
                        color: "red",
                        playerId: 1
                    },
                    {
                        color: "magenta",
                        playerId: 1
                    },
                    {
                        color: "yellow",
                        playerId: 1
                    },
                    {
                        color: "white",
                        playerId: 1
                    },
                    {
                        color: "blue",
                        playerId: 1
                    }
                ],
                [
                    {
                        color: "blue",
                        playerId: 1
                    },
                    {
                        color: "green",
                        playerId: 1
                    },
                    {
                        color: "cyan",
                        playerId: 1
                    },
                    {
                        color: "red",
                        playerId: 1
                    },
                    {
                        color: "magenta",
                        playerId: 1
                    },
                    {
                        color: "yellow",
                        playerId: 1
                    },
                    {
                        color: "white",
                        playerId: 1
                    }
                ],
                [
                    {
                        color: "blue",
                        playerId: 1
                    },
                    {
                        color: "green",
                        playerId: 1
                    },
                    {
                        color: "cyan",
                        playerId: 1
                    },
                    {
                        color: "red",
                        playerId: 1
                    },
                    {
                        color: "magenta",
                        playerId: 1
                    },
                    {
                        color: "yellow",
                        playerId: 1
                    },
                    {
                        color: "white",
                        playerId: 1
                    },
                    {
                        color: "blue",
                        playerId: 1
                    }
                ]
            ]
        };
    },
    methods: {
        onSelected(color, player) {}
    }
};
</script>
