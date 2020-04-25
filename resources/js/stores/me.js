import axios from "axios";
import { notify } from "../components/Messaging/notify";
import { azByPropetryName } from "../libs/sorting";
import { Day } from "../libs/Calendar/Day";
import { TimePeriod } from "../libs/Calendar/TimePeriod";

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
        available_periods: []
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

        availableHoursSummary: state => {
            return [0, 1, 2, 3, 4, 5, 6].map(day_int => {
                const day = state.available_periods.find(
                    day => parseInt(day.day) === day_int
                );
                if (day) {
                    return new Day(
                        day_int,
                        day.periods.map(
                            period => new TimePeriod(period.starts, period.ends)
                        )
                    );
                }
                return new Day(day_int, []);
            });
        },

        availablePeriodsForDay: state => day_of_week => {
            const day = state.available_periods.find(
                d => parseInt(d.day) === parseInt(day_of_week)
            );

            if (!day) {
                return [];
            }

            return day.periods.map(p => new TimePeriod(p.starts, p.ends));
        }
    },

    mutations: {
        setUser(state, { name, email, is_admin, is_teacher }) {
            state.name = name;
            state.email = email;
            state.is_teacher = is_teacher;
            state.is_admin = is_admin;
        },

        setNationalities(state, nationalities) {
            state.nationalities = nationalities;
        },

        setAvailablePeriods(state, periods) {
            state.available_periods = periods;
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

        fetchProfile({ state }) {
            return axios
                .get("/admin/api/me/profile")
                .then(({ data }) => (state.profile = data));
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
                        dispatch("fetchProfile");
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
        }
    }
};
