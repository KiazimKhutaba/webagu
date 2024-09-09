/**
 * Remove duplicate object from list (array) by comparing their keys
 *
 * @param listOfObjects {Array<Object>}
 * @param key
 */
export default function removeDuplicates(listOfObjects, key) {
    const resultList = [];
    for (const obj of listOfObjects) {
        if(resultList.find(o => o[key] === obj[key])) continue;
        resultList.push(obj);
    }
    return resultList;
}
