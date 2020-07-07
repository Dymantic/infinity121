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
import TeacherLocationsForm from "../components/Locations/TeacherLocationsForm";
import MySchedule from "../components/Calendar/MySchedule";
import MyLessons from "../components/Lessons/MyLessons";
import HelpPage from "../components/Misc/HelpPage";

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
    },
    {
        path: "/me/working-areas/edit",
        component: TeacherLocationsForm
    },
    { path: "/me/my-schedule", component: MySchedule },
    { path: "/me/my-lessons", component: MyLessons },
    { path: "/help-me", component: HelpPage }
];
