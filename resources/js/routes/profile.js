import ProfilePage from "../components/Profiles/MainPage";
import ProfileShow from "../components/Profiles/Show";
import ProfileEdit from "../components/Profiles/Edit";
import ResetPassword from "../components/Profiles/ResetPassword";
import UpdateUserInfo from "../components/Users/UpdateUserInfo";
import AvailablePeriods from "../components/Calendar/AvailablePeriods";
import AvailablePeriodsEdit from "../components/Calendar/AvailablePeriodsEdit";

export default [
    {
        path: "/me",
        component: ProfilePage,
        children: [
            { path: "show", component: ProfileShow },
            { path: "edit", component: ProfileEdit }
        ]
    },
    { path: "/", redirect: "/me/show" },
    { path: "/me/password/edit", component: ResetPassword },
    { path: "/me/user-info", component: UpdateUserInfo },
    { path: "/me/available-periods", component: AvailablePeriods },
    { path: "/me/available-periods/edit/:day", component: AvailablePeriodsEdit }
];
