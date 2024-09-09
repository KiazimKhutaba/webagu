<script>

import {quizTypeTranslationsRu} from "@/modules/quiz/utils/QuizType.js";

export default {
    name: "QuizTypeBadge",

    props: {
        type: {
            type: String,
            required: true,
        }
    },

    computed: {
        /**
         *
         * @private
         */
        _type() {
            return quizTypeTranslationsRu.find(q => q.type === this.type);
        }
    }
}
</script>

<template>
    <span class="badge" :class="'bg-' + _type?.style">{{ _type?.value }}</span>
</template>

<style scoped>

</style>
