import { deleteRequest, post } from "./http";

function subscribeUserToAdminEmails(user_id) {
    return post("/admin/api/admin-email-subscriptions", { user_id });
}

function unsubscribeUserFromAdminEmails(user_id) {
    return deleteRequest(`/admin/api/admin-email-subscriptions/${user_id}`);
}

export { subscribeUserToAdminEmails, unsubscribeUserFromAdminEmails };
