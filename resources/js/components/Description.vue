<template>
    <div
        id="descriptionCarousel"
        class="carousel slide px-5 pt-3 pb-5"
        data-ride="carousel"
    >
        <ol class="carousel-indicators">
            <li
                v-for="(slide, index) in slides"
                :key="index"
                data-target="#descriptionCarousel"
                :data-slide-to="index"
                :class="{ active: index === 0 }"
            ></li>
        </ol>
        <div class="carousel-inner">
            <div
                v-for="(slide, index) in slides"
                :key="index"
                :class="['carousel-item', { active: index === 0 }]"
                style="height: 70px"
            >
                <p class="px-3 text-left">
                    {{ slide }}
                </p>
            </div>
        </div>
        <a
            class="carousel-control-prev"
            href="#descriptionCarousel"
            role="button"
            data-slide="prev"
        >
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a
            class="carousel-control-next"
            href="#descriptionCarousel"
            role="button"
            data-slide="next"
        >
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</template>

<script>
export default {
    name: "Description",
    data() {
        return {
            slides: [
                `Игра проходит на поле, состоящем из квадратных
                («ромбических» — повёрнутых так, что диагонали клеток
                располагаются по вертикали и горизонтали) клеток нескольких
                разных цветов.`,
                `В начале игры клетки раскрашены случайным образом (как
                правило, объединены в кластеры по несколько). Каждый игрок
                начинает игру со своей стартовой клетки, находящейся на краю
                поля (в углу).`,
                `На каждом ходу игрок изменяет цвет стартовой клетки на любой
                другой — при этом все клетки, примыкающие к стартовой по
                стороне и окрашенные в тот же цвет, также перекрашиваются в
                выбранный цвет.`,
                `Таким образом игрок «захватывает» соседние клетки,
                перекрашивая свою область в цвет этих клеток. Игрок не может
                выбрать цвет, которым на этом ходу окрашена область
                противника.`,
                `Цель игры состоит в захвате более половины клеток игрового
                поля.`
            ]
        };
    }
};
</script>

<style scoped>
.carousel-control-next,
.carousel-control-prev,
.carousel-indicators {
    filter: invert(100%);
}

.carousel-control-prev {
    margin-left: -30px;
}

.carousel-control-next {
    margin-right: -30px;
}
</style>
