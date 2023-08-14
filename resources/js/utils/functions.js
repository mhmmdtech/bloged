export function shortenText(text, length = 100, suffix = "...") {
    if (text.length <= length) return text;

    let shortText = text.substr(0, length);
    let lastSpace = shortText.lastIndexOf(" ");

    if (lastSpace !== -1) shortText = shortText.substr(0, lastSpace);

    return shortText.trim() + suffix;
}

export function syncArray(array, item) {
    const index = array.indexOf(item);

    if (index !== -1) {
        // Item exists in the array, so remove it
        array.splice(index, 1);
        return;
    }

    // Item does not exist in the array, so add it
    array.push(item);
}

export function removeNullFromArray(data) {
    return Object.fromEntries(
        Object.entries(data).filter(
            ([key, value]) => value !== null && value !== ""
        )
    );
}

export function parseQueryString(queryString) {
    const params = {};
    const keyValuePairs = queryString.split("&");

    for (let i = 0; i < keyValuePairs.length; i++) {
        const [key, value] = keyValuePairs[i].split("=");
        params[key] = decodeURIComponent(value);
    }

    return params;
}

export function convertUtcToLocalDate(utcDate) {
    const utcMilliseconds = new Date(utcDate).getTime();
    const timezoneOffsetMinutes = new Date(utcDate).getTimezoneOffset();
    const timezoneOffsetMilliseconds =
        Math.abs(timezoneOffsetMinutes) * 60 * 1000;
    const localMilliseconds =
        utcMilliseconds +
        (timezoneOffsetMinutes < 0 ? 1 : -1) * timezoneOffsetMilliseconds;
    return new Date(localMilliseconds);
}
