import axios from "axios";
import { notify } from "../components/Messaging/notify";
import { intByPropertyName } from "../libs/sorting";
import { availableTeachers } from "../api/teachers";

export default {
    namespaced: true,

    state: {
        teachers: []
    },

    getters: {
        byId: state => id => state.teachers.find(teacher => teacher.id === id),

        ordered: state => state.teachers.sort(intByPropertyName("position"))
    },

    mutations: {
        setTeachers(state, teachers) {
            state.teachers = teachers;
        }
    },

    actions: {
        fetchTeachers({ commit }) {
            return new Promise((resolve, reject) => {
                axios
                    .get("/admin/api/profiles")
                    .then(({ data }) => {
                        commit("setTeachers", data);
                        resolve();
                    })
                    .catch(() =>
                        reject({ message: "Unable to fetch teacher profiles." })
                    );
            });
        },

        fetchTeacherById({ getters }, id) {
            return new Promise((resolve, reject) => {
                const in_store = getters.byId(id);

                if (in_store) {
                    return resolve(in_store);
                }

                axios
                    .get(`/admin/api/profiles/${id}`)
                    .then(({ data }) => resolve(data))
                    .catch(() =>
                        reject({ message: "Unable to fetch teacher info" })
                    );
            });
        },

        publishTeacher({ dispatch }, teacher_id) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/admin/api/published-profiles", {
                        profile_id: teacher_id
                    })
                    .then(() => {
                        dispatch("fetchTeachers").catch(notify.error);
                        resolve();
                    })
                    .catch(() =>
                        reject({ message: "Unable to set teacher as active." })
                    );
            });
        },

        retractTeacher({ dispatch }, teacher_id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/admin/api/published-profiles/${teacher_id}`)
                    .then(() => {
                        dispatch("fetchTeachers").catch(notify.error);
                        resolve();
                    })
                    .catch(() =>
                        reject({
                            message: "Unable to mark teacher as inactive"
                        })
                    );
            });
        },

        assignCourses({ dispatch }, { teacher_id, subject_ids }) {
            return new Promise((resolve, reject) => {
                axios
                    .post(`/admin/api/profiles/${teacher_id}/subjects`, {
                        subject_ids
                    })
                    .then(() => {
                        dispatch("fetchTeachers").catch(notify.error);
                        resolve({ message: "Courses assigned successfully." });
                    })
                    .catch(() =>
                        reject({
                            message: "Unable to assign courses to teacher."
                        })
                    );
            });
        },

        setOrder({ dispatch }, order) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/admin/api/profiles-order", { order })
                    .then(() => {
                        dispatch("fetchTeachers").catch(notify.error);
                        resolve();
                    })
                    .catch(() => reject({ message: "Unable to set order." }));
            });
        },

        queryAvailableTeachers({}, { subject_id, area_id, lesson_blocks }) {
            return availableTeachers(subject_id, area_id, lesson_blocks);
        }
    }
};
