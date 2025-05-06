import DashboardLayout from "@/pages/Layout/DashboardLayout.vue";
import Dashboard from "@/pages/Dashboard.vue";
import UserProfile from "@/pages/UserProfile.vue";
import TableList from "@/pages/TableList.vue";
import Login from "@/pages/Login.vue";

const routes = [
  {
    path: "/login",
    name: "Login",
    component: Login,
  },
  {
    path: "/",
    component: DashboardLayout,
    redirect: "/dashboard",
    children: [
      {
        path: "dashboard",
        name: "Dashboard",
        component: Dashboard,
        beforeEnter: (to, from, next) => {
          const user = JSON.parse(localStorage.getItem("user"));
          if (user) {
            next();
          } else {
            next("/login");
          }
        },
      },
      {
        path: "user",
        name: "User Profile",
        component: UserProfile,
        beforeEnter: (to, from, next) => {
          const user = JSON.parse(localStorage.getItem("user"));
          if (user) {
            next();
          } else {
            next("/login");
          }
        },
      },
      {
        path: "table",
        name: "File List",
        component: TableList,
        beforeEnter: (to, from, next) => {
          const user = JSON.parse(localStorage.getItem("user"));
          if (user) {
            next();
          } else {
            next("/login");
          }
        },
      },
    ],
  },
];

export default routes;
