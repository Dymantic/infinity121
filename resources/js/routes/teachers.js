import TeacherProfilesPage from "../components/Teachers/TeacherProfilesPage";
import ManageTeacherPage from "../components/Teachers/ManageTeacherPage";

export default [
    {
        path: '/teachers',
        component: TeacherProfilesPage,
    },
    {
        path: '/teachers/:id',
        component: ManageTeacherPage,
    },

];
