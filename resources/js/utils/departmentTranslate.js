export default function departmentTr(n) {
    return {
        finance: 'Финансы',
        accountant: 'Бухчет',
        national: 'Нацэкономика'
    }[n] || 'Неизвестный';
};
