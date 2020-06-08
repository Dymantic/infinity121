import { post } from "./http";

function fetchCustomers() {
    return new Promise((resolve, reject) => {
        axios
            .get("/admin/api/customers")
            .then(({ data }) => resolve(data))
            .catch(() => reject({ message: "Unable to fetch customers" }));
    });
}

function fetchCustomerById(id) {
    return new Promise((resolve, reject) => {
        axios
            .get(`/admin/api/customers/${id}`)
            .then(({ data }) => resolve(data))
            .catch(() => reject({ message: "Unable to find customer" }));
    });
}

function createCustomer(formData) {
    return post("/admin/api/customers", formData);
}

function updateCustomerInfo(customer_id, formData) {
    return post(`/admin/api/customers/${customer_id}`, formData);
}

function destroyCustomer(id) {
    return new Promise((resolve, reject) => {
        axios
            .delete(`/admin/api/customers/${id}`)
            .then(() => resolve({ message: "Customer deleted" }))
            .catch(() => reject({ message: "Unable to delete customer" }));
    });
}

export {
    fetchCustomers,
    fetchCustomerById,
    createCustomer,
    updateCustomerInfo,
    destroyCustomer
};
