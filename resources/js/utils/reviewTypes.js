export const reviewTypes = () => ([
    { name: 'accepted', caption: 'Принято', clazz: 'success' },
    { name: 'rejected', caption: 'Не сдано', clazz: 'danger' },
    { name: 'returned', caption: 'Возвращено на доработку', clazz: 'secondary' },
    { name: 'wait_for_review', caption: 'Ожидает проверки', clazz: 'primary' },
    { name: 'awaiting_execution', caption: 'Ожидает выполнения', clazz: 'warning' },
]);

export const defaultType = reviewTypes()[4];

export function getReviewByStatusName(name) {
    return reviewTypes().find(type => type.name === name);
}
