import axios from "axios";
import {azByPropetryName} from "../libs/sorting";
import {notify} from "../components/Messaging/notify";

export default {
    namespaced: true,

    state: {
        affiliates: [],
    },

    getters: {
        sorted: state => state.affiliates.sort(azByPropetryName('name.en')),

        byId: state => id => state.affiliates.find(affiliate => affiliate.id === parseInt(id)),
    },

    mutations: {
        setAffiliates(state, affiliates) {
            state.affiliates = affiliates;
        }
    },

    actions: {
        fetchAffiliates({commit}) {
            return new Promise((resolve, reject) => {
               axios.get("/admin/api/affiliates")
                   .then(({data}) => {
                       commit('setAffiliates', data);
                       resolve();
                   })
                   .catch(() => reject({message: 'Unable to fetch affiliates.'}));
            });
        },

        fetchById(_, id) {
            return new Promise((resolve, reject) => {
               axios.get(`/admin/api/affiliates/${id}`)
                   .then(({data}) => resolve(data))
                   .catch(() => reject({message: 'Unable to fetch affiliate.'}));
            });
        },

        addAffiliate({dispatch}, affiliate_data) {
            return new Promise((resolve, reject) => {
               axios.post("/admin/api/affiliates", affiliate_data)
                   .then(({data}) => {
                       dispatch('fetchAffiliates').catch(notify.error);
                       resolve(data);
                   })
                   .catch(({response}) => reject(response));
            });
        },

        updateAffiliate({dispatch}, {id, data}) {
            return new Promise((resolve, reject) => {
               axios.post(`/admin/api/affiliates/${id}`, data)
                   .then(({data}) => {
                       dispatch('fetchAffiliates').catch(notify.error);
                       resolve(data);
                   })
                   .catch(({response}) => reject(response));
            });
        },

        publishAffiliate({dispatch}, affiliate_id) {
            return new Promise((resolve, reject) => {
               axios.post("/admin/api/published-affiliates", {affiliate_id})
                   .then(() => {
                       dispatch('fetchAffiliates').catch(notify.error);
                       resolve({message: 'The affiliate has been published'});
                   })
                   .catch(() => reject({message: 'Unable to publish affiliate'}));
            });
        },

        retractAffiliate({dispatch}, affiliate_id) {
            return new Promise((resolve, reject) => {
                axios.delete(`/admin/api/published-affiliates/${affiliate_id}`)
                     .then(() => {
                         dispatch('fetchAffiliates').catch(notify.error);
                         resolve({message: 'The affiliate has been retracted'});
                     })
                     .catch(() => reject({message: 'Unable to retract affiliate'}));
            });
        },

        deleteAffiliate({dispatch}, affiliate_id) {
            return new Promise((resolve, reject) => {
               axios.delete(`/admin/api/affiliates/${affiliate_id}`)
                   .then(() => {
                       dispatch('fetchAffiliates').catch(notify.error);
                       resolve({message: "Affiliate has been deleted"});
                   })
                   .catch(() => reject({message: 'Unable to delete affiliate'}));
            });
        }
    }
}
