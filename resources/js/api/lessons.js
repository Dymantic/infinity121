import { get, post } from "./http";

function fetchLogged() {
    return get("/admin/api/logged-lessons");
}

function fetchDueLogging() {
    return get("/admin/api/due-logging-lessons");
}

function fetchMyDueLessons() {
    return get("/admin/api/me/due-lessons");
}

function fetchMyCompletedLessons() {
    return get("/admin/api/me/completed-lessons");
}

function logLesson(lesson_id, formData) {
    return post(`/admin/api/lessons/${lesson_id}/log`, formData);
}

export {
    fetchLogged,
    fetchDueLogging,
    fetchMyDueLessons,
    fetchMyCompletedLessons,
    logLesson
};
