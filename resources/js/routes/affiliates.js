import Index from "../components/Affiliates/Index";
import CreateAffiliate from "../components/Affiliates/CreateAffiliate";
import Show from "../components/Affiliates/Show";
import Edit from "../components/Affiliates/Edit";

export default [
    {path: '/affiliates', component: Index},
    {path: '/affiliates/create', component: CreateAffiliate},
    {path: '/affiliates/:id', component: Show},
    {path: '/affiliates/:id/edit', component: Edit},

];
