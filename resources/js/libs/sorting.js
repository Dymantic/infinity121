function intByPropertyName(name) {

    return (a,b) => {
        const resolved_a = resolve(name, a);
        const resolved_b = resolve(name, b);

        return resolved_a - resolved_b;
    };
}


function azByPropetryName(name) {

    return (a,b) => {
        const resolved_a = resolve(name, a);
        const resolved_b = resolve(name, b);
        if(resolved_a > resolved_b) {
            return 1;
        }

        if(resolved_a < resolved_b) {
            return -1;
        }

        return 0;
    }
}

function resolve(path, obj) {
    return path.split(".").reduce((acc, key) => {
        return acc[key] || {};
    }, obj)
}

export {azByPropetryName, intByPropertyName};
