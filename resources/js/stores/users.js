import axios from "axios";
import { notify } from "../components/Messaging/notify";
import {
    subscribeUserToAdminEmails,
    unsubscribeUserFromAdminEmails
} from "../api/users";

export default {
    namespaced: true,

    state: {
        users: []
    },

    getters: {
        admins: state => state.users.filter(u => u.is_admin),

        teachers: state => state.users.filter(u => !u.is_admin && u.is_teacher),

        byId: state => id =>
            state.users.find(u => parseInt(u.id) === parseInt(id))
    },

    mutations: {
        setUsers(state, users) {
            state.users = users;
        }
    },

    actions: {
        fetchAllUsers({ commit }) {
            return new Promise((resolve, reject) => {
                axios
                    .get("/admin/api/users")
                    .then(({ data }) => {
                        commit("setUsers", data);
                        resolve();
                    })
                    .catch(() => reject({ message: "Unable to fetch users" }));
            });
        },

        removeUser({ dispatch }, id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/admin/api/users/${id}`)
                    .then(() => {
                        dispatch("fetchAllUsers").catch(notify.error);
                        resolve();
                    })
                    .catch(() => reject({ message: "Unable to delete user." }));
            });
        },

        subscribeUserToEmails({ dispatch }, { user_id }) {
            return subscribeUserToAdminEmails(user_id).then(() =>
                dispatch("fetchAllUsers").catch(notify.error)
            );
        },

        unsubscribeUserFromEmails({ dispatch }, { user_id }) {
            return unsubscribeUserFromAdminEmails(user_id).then(() =>
                dispatch("fetchAllUsers").catch(notify.error)
            );
        }
    }
};
