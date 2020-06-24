function listStudentNames(students) {
    const names = students.map(s => s.name);

    if (names.length > 2) {
        return `${names[0]}, ${names[1]} + ${names.length - 2} more`;
    }

    if (names.length === 2) {
        return `${names[0]} and ${names[1]}`;
    }

    return names[0];
}

export { listStudentNames };
