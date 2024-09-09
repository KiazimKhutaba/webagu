export default function shuffleList(list, count = 1) {
    const copy = [...list];
    const items = [];
    for (let i = 0; i <= count - 1; i++) {
        const randomListIndex = Math.floor((Math.random() * (copy.length)));
        items.push(copy[randomListIndex]);

        // eliminating repeat
        copy.splice(randomListIndex, 1);
    }
    return items;
}
