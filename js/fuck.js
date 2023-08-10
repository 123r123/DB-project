function isFloat(val) {
    var floatRegex = /^-?\d+(?:[.,]\d*?)?$/;
    if (!floatRegex.test(val))
        return false;

    val = parseFloat(val);
    if (isNaN(val) == 1)
        return false;
    return true;
}

function toPage(link) {
    window.location.replace(link);
}