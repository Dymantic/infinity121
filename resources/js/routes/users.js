import UsersPage from "../components/Users/UsersPage";
import ShowUser from "../components/Users/ShowUser"

export default [
    {path: '/users', component: UsersPage},
    {path: '/users/:id', component: ShowUser},

]
