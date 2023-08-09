export function shortenText(text, length = 100, suffix = "...") {
    if (text.length <= length) return text;

    let shortText = text.substr(0, length);
    let lastSpace = shortText.lastIndexOf(" ");

    if (lastSpace !== -1) shortText = shortText.substr(0, lastSpace);

    return shortText.trim() + suffix;
}
