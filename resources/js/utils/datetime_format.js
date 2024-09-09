export default function datetime_format(timestamp, options = null) {

    const _options = options || {
        weekday: "short",
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "numeric",
        minute: "numeric"
    };

    if(!timestamp) return '';
    let dt_formatted = '';

    const dtFormat = new Intl.DateTimeFormat("ru", _options);

    try {
        dt_formatted = dtFormat.format(new Date(timestamp));
    }
    catch (e) {
        return '';
    }

    return dt_formatted;
}
