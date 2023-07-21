import { createWebHistory, createRouter } from "vue-router";
import Home from "@/Pages/Dashboard.vue";
import Reports from "@/Pages/Reports.vue";
import Students from "@/Pages/Students.vue";
import StudentsSearch from "@/Pages/SearchResults.vue";

const routes = [
    {
        path: "/dashboard",
        name: "Home",
        component: Home,
    },
    {
        path: "/students",
        name: "Students",
        component: Students
    },
    {
        path: "/students-search",
        name: "StudentsSearch",
        component: StudentsSearch,
        props: true
    },


];
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
// const router = new VueRouter({
//     mode: 'history',
//     scrollBehavior (to, from, savedPosition) {
//         return { x: 0, y: 0 }
//     },
//     routes
// });
const router = createRouter({
    mode: 'history',
    history: createWebHistory(),
    routes,
    scrollBehavior (to, from, savedPosition) {
        return { x: 0, y: 0 }
    },
});

export default router;
