import axios from "axios";

export default {
    namespaced: true,
    state: {
        profile: {
            name: '',
            bio: '',
            nationality: '',
            years_experience: 0,
            chinese_ability: 1,
            teaching_specialities: '',
            qualifications: ''
        },
        chinese_abilities: [
            {
                key: 'zhab1',
                value: 1,
                description: 'A little',
            },
            {
                key: 'zhab2',
                value: 2,
                description: 'Some',
            },
            {
                key: 'zhab3',
                value: 3,
                description: 'Good',
            },
            {
                key: 'zhab4',
                value: 4,
                description: 'Very good',
            },
        ]
    },

    getters: {
        chineseLevel: (state) => (val) => {
            const ability = state.chinese_abilities.find(ability => ability.value === val);

            return ability ? ability.description : 'Unknown';
        }
    },

    actions: {
        fetchProfile({state}) {
            return axios.get("/admin/me/profile")
                .then(({data}) => state.profile = data);
        },

        saveProfile({dispatch}, formData) {
            return new Promise((resolve, reject) => {
                axios.post(`/admin/profiles/${formData.id}`, formData)
                     .then(() => {
                         dispatch('fetchProfile');
                         resolve();
                     })
                    .catch(({response}) => {
                        reject({
                            status: response.status,
                            data: response.data,
                        });
                    })
            });

        }
    }
};