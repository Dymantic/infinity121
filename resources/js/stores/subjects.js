import axios from "axios";
import {notify} from "../components/Messaging/notify";
import {intByPropertyName} from "../libs/sorting";

export default {
    namespaced: true,

    state: {
        subjects: [],
    },

    getters: {
        sorted_subjects: state => {
          return  state.subjects.sort(intByPropertyName('position'));
        },

        byId: state => id => state.subjects.find(subject => subject.id === id),
    },

    actions: {
        fetchSubjects({state}) {
            return new Promise((resolve, reject) => {
                axios.get("/admin/api/subjects")
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
                axios.post("/admin/api/subjects", {title})
                    .then(({data}) => resolve(data))
                    .catch(({response}) => reject(response));
            });
        },

        saveSubject({state, dispatch}, {id, formData}) {
            return new Promise((resolve, reject) => {
                axios.post(`/admin/api/subjects/${id}`, formData)
                    .then(() => {
                        dispatch('fetchSubjects');
                        resolve();
                    })
                    .catch(({response}) => reject(response))
            });
        },

        deleteSubject({dispatch}, subject_id) {
            return new Promise((resolve, reject) => {
               axios.delete(`/admin/api/subjects/${subject_id}`)
                   .then(() => {
                       dispatch('fetchSubjects').catch(notify.error);
                       resolve();
                   })
                   .catch(() => reject({message: 'Failed to delete course.'}));
            });
        },

        publishSubject({dispatch}, subject_id) {
            return new Promise((resolve, reject) => {
               axios.post("/admin/api/public-subjects", {subject_id})
                   .then(() => {
                       dispatch('fetchSubjects').catch(notify.error);
                       resolve({message: 'Subject has been updated'});
                   })
                   .catch(() => reject({message: 'Unable to publish subject.'}))
            });
        },

        retractSubject({dispatch}, subject_id) {
            return new Promise((resolve, reject) => {
                axios.delete(`/admin/api/public-subjects/${subject_id}`)
                     .then(() => {
                         dispatch('fetchSubjects').catch(notify.error);
                         resolve({message: 'Subject has been retracted'});
                     })
                     .catch(() => reject({message: 'Unable to retract subject.'}))
            });
        },

        orderSubjects({dispatch}, subject_ids) {
            return new Promise((resolve, reject) => {
               axios.post("/admin/api/subjects-order", {subject_ids})
                   .then(() => {
                       dispatch('fetchSubjects').catch(notify.error);
                       resolve();
                   })
                   .catch(() => reject({message: 'Unable to set subject order'}));
            });
        }


    }
};
