function availableTeachers(subject_id, area_id, lesson_blocks) {
    return new Promise((resolve, reject) => {
        axios
            .post("/admin/api/available-teachers", {
                subject_id,
                area_id,
                lesson_blocks
            })
            .then(({ data }) => resolve(data))
            .catch(() =>
                reject({ message: "Unable to query available teachers" })
            );
    });
}

export { availableTeachers };
