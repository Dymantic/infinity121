import axios from "axios";
import { notify } from "../components/Messaging/notify";
import { azByPropetryName } from "../libs/sorting";
import { Day } from "../libs/Calendar/Day";
import { TimePeriod } from "../libs/Calendar/TimePeriod";
import {
    fetchMyCompletedLessons,
    fetchMyDueLessons,
    logLesson
} from "../api/lessons";

export default {
    namespaced: true,

    state: {
        name: "",
        email: "",
        is_admin: "",
        is_teacher: "",
        profile: {
            name: "",
            bio: "",
            nationality: "",
            years_experience: 0,
            chinese_ability: 1,
            teaching_specialities: "",
            qualifications: ""
        },
        chinese_abilities: [
            { value: 1, description: "A little" },
            { value: 2, description: "Some" },
            { value: 3, description: "Good" },
            { value: 4, description: "Very good" }
        ],
        languages: [
            { code: "en", name: "English" },
            { code: "sp", name: "Spanish" },
            { code: "jp", name: "Japanese" },
            { code: "zh", name: "Chinese" },
            { code: "fr", name: "French" },
            { code: "de", name: "German" }
        ],
        nationalities: {},
        available_periods: {
            0: { periods: [] },
            1: { periods: [] },
            2: { periods: [] },
            3: { periods: [] },
            4: { periods: [] },
            5: { periods: [] },
            6: { periods: [] }
        },
        schedule: {
            available: {
                0: { periods: [] },
                1: { periods: [] },
                2: { periods: [] },
                3: { periods: [] },
                4: { periods: [] },
                5: { periods: [] },
                6: { periods: [] }
            },
            confirmed: {
                0: { periods: [] },
                1: { periods: [] },
                2: { periods: [] },
                3: { periods: [] },
                4: { periods: [] },
                5: { periods: [] },
                6: { periods: [] }
            },
            unconfirmed: {
                0: { periods: [] },
                1: { periods: [] },
                2: { periods: [] },
                3: { periods: [] },
                4: { periods: [] },
                5: { periods: [] },
                6: { periods: [] }
            }
        },
        unavailable_periods: [],
        due_lessons: [],
        completed_lessons: []
    },

    getters: {
        details: state => {
            return {
                name: state.name,
                email: state.email,
                is_teacher: state.is_teacher,
                is_admin: state.is_admin
            };
        },

        name: state => state.profile.name,

        avatar: state => state.profile.avatar_thumb,

        sortedNationalities: state => {
            return Object.keys(state.nationalities)
                .map(code => ({ code, name: state.nationalities[code] }))
                .sort(azByPropetryName("name"));
        },

        currentScheduleSummary: state => {
            return [0, 1, 2, 3, 4, 5, 6].map(day => {
                return new Day(
                    day,
                    state.schedule.available[day].periods.map(
                        p => new TimePeriod(p.starts, p.ends)
                    ),
                    state.schedule.confirmed[day].periods.map(
                        p => new TimePeriod(p.starts, p.ends)
                    ),
                    state.schedule.unconfirmed[day].periods.map(
                        p => new TimePeriod(p.starts, p.ends)
                    )
                );
            });
        },

        availableHoursSummary: state => {
            return Object.keys(state.available_periods).map(day => {
                return new Day(
                    day,
                    state.available_periods[day].periods.map(
                        p => new TimePeriod(p.starts, p.ends)
                    )
                );
            });
        },

        availablePeriodsForDay: state => day_of_week => {
            return state.available_periods[day_of_week].periods.map(
                p => new TimePeriod(p.starts, p.ends)
            );
        },

        unavailablePeriodById: state => id =>
            state.unavailable_periods.find(p => p.id === parseInt(id)),

        myWorkingAreas: state => state.profile.working_areas,

        lessonById: state => id => {
            return state.due_lessons.find(l => l.id === parseInt(id));
        },

        loggedLessonById: state => id =>
            state.completed_lessons.find(l => l.id === parseInt(id))
    },

    mutations: {
        setUser(state, { name, email, is_admin, is_teacher }) {
            state.name = name;
            state.email = email;
            state.is_teacher = is_teacher;
            state.is_admin = is_admin;
        },

        setProfile(state, profile) {
            state.profile = profile;
        },

        setNationalities(state, nationalities) {
            state.nationalities = nationalities;
        },

        setAvailablePeriods(state, periods) {
            state.available_periods = periods;
        },

        setUnavailablePeriods(state, periods) {
            state.unavailable_periods = periods;
        },

        setCurrentSchedule(state, schedule) {
            state.schedule = schedule;
        },

        setDueLessons(state, lessons) {
            state.due_lessons = lessons;
        },

        setCompletedLessons(state, lessons) {
            state.completed_lessons = lessons;
        }
    },

    actions: {
        hydrateFromPage({ commit }) {
            commit("setUser", window.current_user);
        },

        fetchCurrentUser({ commit }) {
            return new Promise((resolve, reject) => {
                axios
                    .get("/admin/api/me")
                    .then(({ data }) => {
                        commit("setUser", data);
                        resolve();
                    })
                    .catch(() => reject({ message: "Unable to fetch user" }));
            });
        },

        updateCurrentUser({ dispatch }, { name, email }) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/admin/api/me", { name, email })
                    .then(() => {
                        dispatch("fetchCurrentUser").catch(notify.error);
                        resolve();
                    })
                    .catch(({ response }) => reject(response));
            });
        },

        fetchProfile({ commit }) {
            return new Promise((resolve, reject) => {
                axios
                    .get("/admin/api/me/profile")
                    .then(({ data }) => {
                        commit("setProfile", data);
                        resolve(data);
                    })
                    .catch(() =>
                        reject({ message: "Unable to fetch your profile" })
                    );
            });
        },

        fetchNationalities({ commit }) {
            return new Promise((resolve, reject) => {
                axios
                    .get("/admin/api/nationalities")
                    .then(({ data }) => {
                        commit("setNationalities", data);
                        resolve();
                    })
                    .catch(() =>
                        reject({
                            message: "Unable to fetch list of nationalities."
                        })
                    );
            });
        },

        saveProfile({ dispatch }, formData) {
            return new Promise((resolve, reject) => {
                axios
                    .post(`/admin/api/profiles/${formData.id}`, formData)
                    .then(() => {
                        dispatch("fetchProfile").catch(notify.error);
                        dispatch("teachers/fetchTeachers", null, {
                            root: true
                        });
                        resolve();
                    })
                    .catch(({ response }) => {
                        reject({
                            status: response.status,
                            data: response.data
                        });
                    });
            });
        },

        logout() {
            return new Promise((resolve, reject) => {
                axios
                    .post("/admin/logout")
                    .then(() => resolve())
                    .catch(() => reject({ message: "Unable to logout" }));
            });
        },

        resetUserPassword(
            {},
            { old_password, new_password, new_password_confirmation }
        ) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/admin/api/me/password", {
                        old_password,
                        new_password,
                        new_password_confirmation
                    })
                    .then(() =>
                        resolve({ message: "Your password has been updated." })
                    )
                    .catch(({ response }) => reject(response));
            });
        },

        fetchAvailablePeriods({ dispatch, commit }) {
            return new Promise((resolve, reject) => {
                axios
                    .get("/admin/api/me/available-periods")
                    .then(({ data }) => {
                        commit("setAvailablePeriods", data);
                        resolve();
                    })
                    .catch(() =>
                        reject({
                            message: "Unable to fetch your available times."
                        })
                    );
            });
        },

        setDailyAvailablePeriods({ dispatch }, { day_of_week, periods }) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/admin/api/me/available-periods", {
                        day_of_week,
                        periods
                    })
                    .then(() => {
                        dispatch("fetchAvailablePeriods").catch(notify.error);
                        resolve();
                    })
                    .catch(() =>
                        reject({ message: "Unable to save available hours" })
                    );
            });
        },

        fetchUnavailablePeriods({ commit }) {
            return new Promise((resolve, reject) => {
                axios
                    .get("/admin/api/me/unavailable-periods")
                    .then(({ data }) => {
                        commit("setUnavailablePeriods", data);
                        resolve();
                    })
                    .catch(() =>
                        reject({
                            message: "Unable to fetch time you are unavailable"
                        })
                    );
            });
        },

        storeUnavailablePeriod({ dispatch }, { formData, id }) {
            const url = id
                ? `/admin/api/me/unavailable-periods/${id}`
                : "/admin/api/me/unavailable-periods";
            return new Promise((resolve, reject) => {
                axios
                    .post(url, formData)
                    .then(() => {
                        dispatch("fetchUnavailablePeriods").catch(notify.error);
                        resolve();
                    })
                    .catch(({ response }) => reject(response));
            });
        },

        deleteUnavailablePeriod({ dispatch }, id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/admin/api/me/unavailable-periods/${id}`)
                    .then(() => {
                        dispatch("fetchUnavailablePeriods").catch(notify.error);
                        resolve();
                    })
                    .catch(() =>
                        reject({ message: "Unable to delete time period." })
                    );
            });
        },

        setWorkingAreas({ dispatch }, area_ids) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/admin/api/me/working-areas", { area_ids })
                    .then(() => {
                        dispatch("fetchProfile").catch(notify.error);
                        resolve({
                            message: "Your working locations have been updated."
                        });
                    })
                    .catch(() =>
                        reject({ message: "Unable to save working locations." })
                    );
            });
        },

        fetchSchedule({ commit }) {
            return new Promise((resolve, reject) => {
                axios
                    .get("/admin/api/me/current-schedule")
                    .then(({ data }) => {
                        commit("setCurrentSchedule", data);
                        resolve();
                    })
                    .catch(() =>
                        reject({ message: "Unable to fetch schedule" })
                    );
            });
        },

        fetchDueLessons({ commit }) {
            return fetchMyDueLessons().then(lessons =>
                commit("setDueLessons", lessons)
            );
        },

        fetchCompletedLessons({ commit }) {
            return fetchMyCompletedLessons().then(lessons =>
                commit("setCompletedLessons", lessons)
            );
        },

        logTeachersLesson({ dispatch }, { lesson_id, formData }) {
            return logLesson(lesson_id, formData).then(() =>
                dispatch("fetchDueLessons").catch(notify.error)
            );
        }
    }
};
