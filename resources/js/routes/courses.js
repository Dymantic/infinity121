import CourseEdit from "../components/Courses/CourseEdit";
import CourseShow from "../components/Courses/CourseShow";

export default [
    { path: "/courses/:id/edit", component: CourseEdit },
    { path: "/courses/:id", component: CourseShow }
];
