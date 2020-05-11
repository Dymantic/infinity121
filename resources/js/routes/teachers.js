import TeacherProfilesPage from "../components/Teachers/TeacherProfilesPage";
import ManageTeacherPage from "../components/Teachers/ManageTeacherPage";
import TeachersOrderPage from "../components/Teachers/TeachersOrderPage";
import EditTeacherProfile from "../components/Profiles/EditTeacherProfile";

export default [
    {
        path: "/teachers",
        component: TeacherProfilesPage
    },
    {
        path: "/sort-teachers",
        component: TeachersOrderPage
    },
    {
        path: "/teachers/:id",
        component: ManageTeacherPage
    },
    {
        path: "/teachers/:id/edit",
        component: EditTeacherProfile
    }
];
