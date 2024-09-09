/**
 *
 * @param file {File}
 * @return {Promise<unknown>}
 */
export default function readFileAsDataURL(file) {

    if(!file) return Promise.reject('No file');

    return new Promise((resolve, reject) => {
        const fileReader = new FileReader();

        fileReader.onload = () => {
            resolve(fileReader.result);
        };

        fileReader.onerror = (error) => {
            reject(error);
        };

        fileReader.readAsDataURL(file);
    });
}