const PassedQuizStatus = Object.freeze({

    Waiting: 'waiting',
    OnReview: 'on_review',
    Reviewed: 'reviewed',

    values() {
        return Object.values(this).filter(v => typeof v === 'string');
    }
});

export {PassedQuizStatus};
