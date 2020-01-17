import axios from "axios";

export default {
    namespaced: true,

    state: {
        users: []
    },

    getters: {
        admins: state => state.users.filter(u => u.is_admin),

        teachers: state => state.users.filter(u => !u.is_admin && u.is_teacher),

        byId: state => id => state.users.find(u => parseInt(u.id) === parseInt(id)),
    },

    mutations: {
        setUsers(state, users) {
            state.users = users;
        }
    },

    actions: {
        fetchAllUsers({commit}) {
            return new Promise((resolve, reject) => {
                axios.get("/admin/api/users")
                     .then(({data}) => {
                         commit('setUsers', data);
                         resolve();
                     })
                     .catch(() => reject({message: 'Unable to fetch users'}));
            });
        }
    }
}
