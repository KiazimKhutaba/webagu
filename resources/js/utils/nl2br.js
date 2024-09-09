/**
 * @link https://stackoverflow.com/questions/7467840/nl2br-equivalent-in-javascripts
 *
 * @param str
 * @param is_xhtml
 * @return {string}
 */
export function nl2br(str, is_xhtml = false) {
    if (typeof str === 'undefined' || str === null) {
        return '';
    }
    const breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}