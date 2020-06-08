function post(url, body) {
    return new Promise((resolve, reject) => {
        axios
            .post(url, body)
            .then(({ data }) => resolve(data))
            .catch(({ response }) => reject(response));
    });
}

export { post };
