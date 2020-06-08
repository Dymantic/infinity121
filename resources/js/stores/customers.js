import { notify } from "../components/Messaging/notify";
import {
    assignCourseTeacher,
    clearCourseTeacher,
    createCourse,
    fetchCourse,
    updateCourseBasicInfo,
    updateCourseLocationInfo,
    updateCourseTimeInfo
} from "../api/courses";
import {
    createCustomer,
    destroyCustomer,
    fetchCustomerById,
    fetchCustomers,
    updateCustomerInfo
} from "../api/customers";

export default {
    namespaced: true,

    state: {
        customers: []
    },

    getters: {
        customerById: state => id =>
            state.customers.find(c => c.id === parseInt(id))
    },

    mutations: {
        setCustomers(state, customers) {
            state.customers = customers;
        }
    },

    actions: {
        fetchCustomers({ commit }) {
            return fetchCustomers().then(customers =>
                commit("setCustomers", customers)
            );
        },

        fetchCustomer({}, id) {
            return fetchCustomerById(id);
        },

        addCustomer({ dispatch }, formData) {
            return createCustomer(formData).then(() => {
                dispatch("fetchCustomers").catch(notify.error);
                return { message: "Customer saved." };
            });
        },

        updateCustomer({ dispatch }, { id, formData }) {
            return updateCustomerInfo(id, formData).then(() => {
                dispatch("fetchCustomers").catch(notify.error);
                return { message: "Customer info updated." };
            });
        },

        deleteCustomer({ dispatch }, id) {
            return destroyCustomer(id).then(message => {
                dispatch("fetchCustomers").catch(notify.error);
                return message;
            });
        },

        fetchCourse({}, id) {
            return fetchCourse(id).catch(notify.error);
        },

        createCustomerCourse({}, { customer_id, formData }) {
            return createCourse(customer_id, formData);
        },

        updateCourseBasic({}, { course_id, formData }) {
            return updateCourseBasicInfo(course_id, formData);
        },

        updateCourseTimes({}, { course_id, formData }) {
            return updateCourseTimeInfo(course_id, formData);
        },

        updateCourseLocation({}, { course_id, formData }) {
            return updateCourseLocationInfo(course_id, formData);
        },

        assignTeacherToCourse({}, { course_id, profile_id }) {
            return assignCourseTeacher(course_id, profile_id);
        },

        removeCourseTeacher({}, course_id) {
            return clearCourseTeacher(course_id);
        }
    }
};
