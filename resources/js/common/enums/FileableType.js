const FileableType = Object.freeze({
    TaskUserReplyFile: 'task.user_reply_file',
    Task: 'task',
    TaskHistory: 'task_history.file',
    LectureFile: 'lecture.file',
    AccountAvatar: 'account.avatar',

    QuizQuiz: 'quiz.quiz',
    QuizQuestion: 'quiz.question',
    QuizTask: 'quiz.task',

    values() {
        return Object.values(this).filter(v => typeof v === 'string');
    }
});

export {FileableType};
