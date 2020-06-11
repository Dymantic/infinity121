function post(url, body) {
    return new Promise((resolve, reject) => {
        axios
            .post(url, body)
            .then(({ data }) => resolve(data))
            .catch(({ response }) => reject(response));
    });
}

function get(url) {
    return new Promise((resolve, reject) => {
        axios
            .get(url)
            .then(({ data }) => resolve(data))
            .catch(({ response }) => reject(response));
    });
}

function deleteRequest(url) {
    return new Promise((resolve, reject) => {
        axios
            .delete(url)
            .then(resolve)
            .catch(({ response }) => reject(response));
    });
}

export { post, get, deleteRequest };
