/**
 *
 * @param pngFile
 * @param maxWidth
 * @return {Promise<unknown>}
 */
export function pngToJpeg(pngFile, maxWidth = 1200) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();

        reader.onload = () => {
            const img = new Image();

            img.onload = () => {
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');

                /*canvas.width = img.width;
                canvas.height = img.height;*/

                const aspectRatio = img.width / img.height;

                const newWidth = maxWidth;
                const newHeight = newWidth / aspectRatio;

                canvas.width = newWidth;
                canvas.height = newHeight;

                context.drawImage(img, 0, 0, newWidth, newHeight);

                canvas.toBlob((blob) => {
                    resolve(blob);
                }, 'image/jpeg');
            };

            img.onerror = (error) => {
                reject(error);
            };

            img.src = reader.result;
        };

        reader.onerror = (error) => {
            reject(error);
        };

        reader.readAsDataURL(pngFile);
    });
}
