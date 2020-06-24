import { fetchActiveCourses } from "../api/courses";
import { fetchDueLogging, fetchLogged } from "../api/lessons";

export default {
    namespaced: true,

    state: {
        active: [],
        logged_lessons: [],
        due_logging: []
    },

    getters: {
        loggedLessonById: state => id =>
            state.logged_lessons.find(l => l.id === parseInt(id)),

        dueLoggingById: state => id =>
            state.due_logging.find(l => l.id === parseInt(id))
    },

    mutations: {
        setActiveCourses(state, courses) {
            state.active = courses;
        },

        setLoggedLessons(state, lessons) {
            state.logged_lessons = lessons;
        },

        setDueLogging(state, lessons) {
            state.due_logging = lessons;
        }
    },

    actions: {
        fetchActive({ commit }) {
            return fetchActiveCourses().then(courses =>
                commit("setActiveCourses", courses)
            );
        },

        fetchLoggedLessons({ commit }) {
            return fetchLogged().then(lessons =>
                commit("setLoggedLessons", lessons)
            );
        },

        fetchDueLoggingLessons({ commit }) {
            return fetchDueLogging().then(lessons =>
                commit("setDueLogging", lessons)
            );
        }
    }
};
