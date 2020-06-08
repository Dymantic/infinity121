import { notify } from "../components/Messaging/notify";

export default {
    namespaced: true,

    state: {
        countries: []
    },

    getters: {
        countryById: state => id =>
            state.countries.find(c => c.id === parseInt(id)),

        regionsOfCountry: state => id => {
            const country = state.countries.find(c => c.id === parseInt(id));
            if (!country) {
                return [];
            }
            return country.regions;
        },

        areasOfRegion: state => id => {
            const country = state.countries.find(c =>
                c.regions.map(r => r.id).includes(parseInt(id))
            );
            if (!country) {
                console.log("no country for old men");
                return [];
            }

            const region = country.regions.find(r => r.id === parseInt(id));
            if (!region) {
                console.log("no region for old men");
                return [];
            }
            return region.areas;
        }
    },

    mutations: {
        setCountries(state, countries) {
            state.countries = countries;
        }
    },

    actions: {
        fetchLocations({ commit }) {
            return new Promise((resolve, reject) => {
                axios
                    .get("/admin/api/countries")
                    .then(({ data }) => {
                        commit("setCountries", data);
                        resolve();
                    })
                    .catch(() =>
                        reject({ message: "Unable to fetch location info" })
                    );
            });
        },

        addCountry({ dispatch }, formData) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/admin/api/countries", formData)
                    .then(() => {
                        dispatch("fetchLocations").catch(notify.error);
                        resolve({
                            message: "Country has been added to locations."
                        });
                    })
                    .catch(({ response }) => reject(response));
            });
        },

        updateCountry({ dispatch }, { country_id, formData }) {
            return new Promise((resolve, reject) => {
                axios
                    .post(`/admin/api/countries/${country_id}`, formData)
                    .then(() => {
                        dispatch("fetchLocations").catch(notify.error);
                        resolve({
                            message: "Country has been updated."
                        });
                    })
                    .catch(({ response }) => reject(response));
            });
        },

        deleteCountry({ dispatch }, id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/admin/api/countries/${id}`)
                    .then(() => {
                        dispatch("fetchLocations").catch(notify.error);
                        resolve();
                    })
                    .catch(() =>
                        reject({ message: "Unable to delete country" })
                    );
            });
        },

        addRegion({ dispatch }, { country_id, formData }) {
            return new Promise((resolve, reject) => {
                axios
                    .post(
                        `/admin/api/countries/${country_id}/regions`,
                        formData
                    )
                    .then(() => {
                        dispatch("fetchLocations").catch(notify.error);
                        resolve({
                            message: "Regions has been saved."
                        });
                    })
                    .catch(({ response }) => reject(response));
            });
        },

        updateRegion({ dispatch }, { region_id, formData }) {
            return new Promise((resolve, reject) => {
                axios
                    .post(`/admin/api/regions/${region_id}`, formData)
                    .then(() => {
                        dispatch("fetchLocations").catch(notify.error);
                        resolve({
                            message: "Regions has been updated."
                        });
                    })
                    .catch(({ response }) => reject(response));
            });
        },

        deleteRegion({ dispatch }, id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/admin/api/regions/${id}`)
                    .then(() => {
                        dispatch("fetchLocations").catch(notify.error);
                        resolve();
                    })
                    .catch(() =>
                        reject({ message: "Unable to delete region" })
                    );
            });
        },

        addArea({ dispatch }, { region_id, formData }) {
            return new Promise((resolve, reject) => {
                axios
                    .post(`/admin/api/regions/${region_id}/areas`, formData)
                    .then(() => {
                        dispatch("fetchLocations").catch(notify.error);
                        resolve({
                            message: "Area has been saved."
                        });
                    })
                    .catch(({ response }) => reject(response));
            });
        },

        updateArea({ dispatch }, { area_id, formData }) {
            return new Promise((resolve, reject) => {
                axios
                    .post(`/admin/api/areas/${area_id}`, formData)
                    .then(() => {
                        dispatch("fetchLocations").catch(notify.error);
                        resolve({
                            message: "Area has been saved."
                        });
                    })
                    .catch(({ response }) => reject(response));
            });
        },

        deleteArea({ dispatch }, id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/admin/api/areas/${id}`)
                    .then(() => {
                        dispatch("fetchLocations").catch(notify.error);
                        resolve();
                    })
                    .catch(() => reject({ message: "Unable to delete area" }));
            });
        }
    }
};
