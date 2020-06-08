function showValidationErrors(formErrors, errors) {
    return Object.keys(formErrors).reduce((acc, key) => {
        acc[key] = errors.hasOwnProperty(key) ? errors[key][0] : "";
        return acc;
    }, {});
}

function clearFormErrors(formErrors) {
    return Object.keys(formErrors).reduce((acc, key) => {
        acc[key] = "";
        return acc;
    }, {});
}

export { showValidationErrors, clearFormErrors };
