import { get, post } from "./http";

function fetchCourse(id) {
    return new Promise((resolve, reject) => {
        axios
            .get(`/admin/api/courses/${id}`)
            .then(({ data }) => resolve(data))
            .catch(() => reject({ message: "Unable to find course." }));
    });
}

function fetchCustomerCourses(customer_id) {
    return get(`/admin/api/customers/${customer_id}/courses`);
}

function createCourse(customer_id, formData) {
    return new Promise((resolve, reject) => {
        axios
            .post(`/admin/api/customers/${customer_id}/courses`, formData)
            .then(({ data }) => resolve(data))
            .catch(({ response }) => reject(response));
    });
}

function updateCourseBasicInfo(course_id, formData) {
    return new Promise((resolve, reject) => {
        axios
            .post(`/admin/api/courses/${course_id}`, formData)
            .then(({ data }) => resolve(data))
            .catch(({ response }) => reject(response));
    });
}

function updateCourseTimeInfo(course_id, formData) {
    return new Promise((resolve, reject) => {
        axios
            .post(`/admin/api/courses/${course_id}/lesson-blocks`, formData)
            .then(({ data }) => resolve(data))
            .catch(({ response }) => reject(response));
    });
}

function updateCourseLocationInfo(course_id, formData) {
    return new Promise((resolve, reject) => {
        axios
            .post(`/admin/api/courses/${course_id}/location`, formData)
            .then(({ data }) => resolve(data))
            .catch(({ response }) => reject(response));
    });
}

function updateCourseTeacher({ course_id, teacher_id }) {
    return new Promise((resolve, reject) => {
        axios
            .post(`/admin/api/courses/${course_id}/teacher`, {
                profile_id: teacher_id
            })
            .then(({ data }) => resolve())
            .catch(({ response }) => reject(response));
    });
}

function assignCourseTeacher(course_id, profile_id) {
    return post(`/admin/api/courses/${course_id}/teacher`, { profile_id });
}

function clearCourseTeacher(course_id) {
    return new Promise((resolve, reject) => {
        axios
            .delete(`/admin/api/courses/${course_id}/teacher`)
            .then(() => resolve({ message: "Teacher removed from course." }))
            .catch(() =>
                reject({ message: "Unable to remove teacher from course." })
            );
    });
}

function confirmCourse(course_id, starts_from) {
    return post("/admin/api/confirmed-courses", { course_id, starts_from });
}

export {
    fetchCourse,
    fetchCustomerCourses,
    updateCourseBasicInfo,
    updateCourseTimeInfo,
    updateCourseLocationInfo,
    updateCourseTeacher,
    createCourse,
    assignCourseTeacher,
    clearCourseTeacher,
    confirmCourse
};
