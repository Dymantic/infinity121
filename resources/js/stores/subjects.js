import axios from "axios";

export default {
    namespaced: true,

    state: {
        subjects: [],
    },

    getters: {
        sorted_subjects: state => {
          return  state.subjects.sort((a, b) => {
                const titleA = a.title['en'].toUpperCase();
                const titleB = b.title['en'].toUpperCase();
                if (titleA < titleB) {
                    return -1;
                }
                if (titleA > titleB) {
                    return 1;
                }
                return 0;
            });
        },
    },

    actions: {
        fetchSubjects({state}) {
            return new Promise((resolve, reject) => {
                axios.get("/admin/subjects")
                     .then(({data}) => {
                         state.subjects = data;
                         resolve();
                     })
                    .catch(() => reject({message: "Failed to fetch subjects"}));
            });
        },

        getSubject({state, dispatch}, id) {
            const subject = state.subjects.find(s => s.id == id);
            if(subject) {
                return Promise.resolve(subject);
            }

            return new Promise((resolve, reject) => {
                dispatch('fetchSubjects')
                    .then(() => {
                        const new_subject = state.subjects.find(s => s.id == id);

                        if(new_subject) {
                            resolve(new_subject);
                        } else {
                            reject();
                        }
                    })
            });
        },

        addSubject({state}, title) {
            return new Promise((resolve, reject) => {
                axios.post("/admin/subjects", {title})
                    .then(({data}) => resolve(data))
                    .catch(({response}) => reject(response));
            });
        },

        saveSubject({state, dispatch}, {id, formData}) {
            return new Promise((resolve, reject) => {
                axios.post(`/admin/subjects/${id}`, formData)
                    .then(() => {
                        dispatch('fetchSubjects');
                        resolve();
                    })
                    .catch(({response}) => reject(response))
            });
        }


    }
};