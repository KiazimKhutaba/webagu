const QuizType = Object.freeze({
    None: 'none',
    Training: 'training',
    Seminar: 'seminar',
    Midterm: 'midterm',
});

const quizTypeTranslationsRu = [
    {
        type: QuizType.Training,
        value: 'Для подготовки',
        style: 'warning',
    },
    {
        type: QuizType.Seminar,
        value: 'Семинар',
        style: 'success',
    },
    {
        type: QuizType.Midterm,
        value: 'Зачет',
        style: 'danger',
    },
]

export {QuizType, quizTypeTranslationsRu};
