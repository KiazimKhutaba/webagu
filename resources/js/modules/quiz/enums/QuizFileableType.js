const QuizFileableType = Object.freeze({

    QuizQuiz: 'quiz.quiz',
    QuizQuestion: 'quiz.question',
    QuizTask: 'quiz.task',

    values() {
        return Object.values(this).filter(v => typeof v === 'string');
    }
});

export {QuizFileableType};
