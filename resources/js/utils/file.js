/**
 * File utils
 */

/**
 *
 * @return {boolean}
 * @param file_or_filename {string|File}
 */
export function is_image(file_or_filename) {

    let filename;

    if (typeof file_or_filename === 'string') {
        filename = file_or_filename.trim().toLowerCase();
    } else if (file_or_filename instanceof File) {
        filename = file_or_filename.name.trim().toLowerCase();
    } else {
        return false;
    }

    if (!filename) {
        return false;
    }

    const extensions = ['png', 'jpeg', 'jpg'];
    const extension = get_file_extension(filename);

    return extensions.includes(extension);
}

/**
 *
 * @link https://stackoverflow.com/a/190878
 * @param filename {string}
 * @return {*}
 */
export function get_file_extension(filename) {
    return filename.split('.').pop();
}
