import TeacherProfilesPage from "../components/Teachers/TeacherProfilesPage";
import ManageTeacherPage from "../components/Teachers/ManageTeacherPage";
import TeachersOrderPage from "../components/Teachers/TeachersOrderPage";

export default [
    {
        path: '/teachers',
        component: TeacherProfilesPage,
    },
    {
        path: '/sort-teachers',
        component: TeachersOrderPage,
    },
    {
        path: '/teachers/:id',
        component: ManageTeacherPage,
    },

];
