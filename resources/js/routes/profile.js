import ProfilePage from "../components/Profiles/MainPage";
import ProfileShow from "../components/Profiles/Show";
import MyProfileEdit from "../components/Profiles/EditMyProfile";
import ResetPassword from "../components/Profiles/ResetPassword";
import UpdateUserInfo from "../components/Users/UpdateUserInfo";
import AvailablePeriods from "../components/Calendar/AvailablePeriods";
import AvailablePeriodsEdit from "../components/Calendar/AvailablePeriodsEdit";
import UnavailablePeriods from "../components/Calendar/UnavailablePeriods";
import CreateUnavailablePeriod from "../components/Calendar/CreateUnavailablePeriod";
import EditUnavailablePeriod from "../components/Calendar/EditUnavailablePeriod";

export default [
    {
        path: "/me",
        component: ProfilePage,
        children: [
            { path: "show", component: ProfileShow },
            { path: "edit", component: MyProfileEdit }
        ]
    },
    { path: "/", redirect: "/me/show" },
    { path: "/me/password/edit", component: ResetPassword },
    { path: "/me/user-info", component: UpdateUserInfo },
    { path: "/me/available-periods", component: AvailablePeriods },
    {
        path: "/me/available-periods/edit/:day",
        component: AvailablePeriodsEdit
    },
    { path: "/me/unavailable-periods", component: UnavailablePeriods },
    {
        path: "/me/unavailable-periods/create",
        component: CreateUnavailablePeriod
    },
    {
        path: "/me/unavailable-periods/:id/edit",
        component: EditUnavailablePeriod
    }
];
