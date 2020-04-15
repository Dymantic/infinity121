import SubjectsIndex from "../components/Subjects/Index";
import SubjectPage from "../components/Subjects/SubjectPage";
import Edit from "../components/Subjects/Edit";
import Show from "../components/Subjects/Show";
import Delete from "../components/Subjects/Delete";
import SubjectsOrderPage from "../components/Subjects/SubjectsOrderPage";

export default [
    {path: '/subjects', component: SubjectsIndex},
    {
        path: '/subjects/:id',
        component: SubjectPage,
        children: [
            {path: 'show', component: Show},
            {path: 'edit', component: Edit},
            {path: 'delete', component: Delete},
        ]
    },
    {path: '/subjects-order', component: SubjectsOrderPage}
];
