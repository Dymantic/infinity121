import CustomerIndex from "../components/Customers/CustomerIndex";
import CustomerShow from "../components/Customers/CustomerShow";
import CustomerEdit from "../components/Customers/CustomerEdit";
import CourseEdit from "../components/Courses/CourseEdit";
import CourseCreate from "../components/Courses/CourseCreate";

export default [
    { path: "/customers", component: CustomerIndex },
    { path: "/customers/:id", component: CustomerShow },
    { path: "/customers/:id/edit", component: CustomerEdit },
    { path: "/customers/:customer/courses/create", component: CourseCreate },
    { path: "/courses/:course/edit", component: CourseEdit }
];
