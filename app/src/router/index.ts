import { useStore } from "@/store/store";
import axios from "axios";
import { inject } from "vue";
import { RouteRecordRaw, createRouter, createWebHashHistory } from "vue-router";

const routes: RouteRecordRaw[] = [
  {
    name: "login",
    path: "/login",
    component: () => import("@views/login.vue"),
    meta: { require_auth: false },
  },
  {
    name: "home",
    path: "/",
    component: () => import("@views/home.vue"),
    meta: { require_auth: true },
  },
  {
    name: "users",
    path: "/users",
    component: () => import("@views/user.vue"),
    meta: { require_auth: true },
  },
  {
    name: "groups",
    path: "/groups",
    component: () => import("@views/group.vue"),
    meta: { require_auth: true },
  },
  {
    name: "clients",
    path: "/clients",
    component: () => import("@views/client.vue"),
    meta: { require_auth: true },
  },
  {
    name: "apps",
    path: "/apps",
    component: () => import("@views/app.vue"),
    meta: { require_auth: true },
  },
  {
    name: "profile",
    path: "/profile",
    component: () => import("@views/profile.vue"),
    meta: { require_auth: true },
  },
  {
    name: "app.users",
    path: "/app/:id/users",
    component: () => import("@views/app/users.vue"),
    meta: { require_auth: true },
  },
  {
    name: "app.document-types",
    path: "/app/:id/document_types",
    component: () => import("@views/app/document-types.vue"),
    meta: { require_auth: true },
  },
];

const router = createRouter({
  routes,
  history: createWebHashHistory(),
});

router.beforeEach(async (to, _from, next) => {
  const store = useStore();
  const http = inject("http", axios);
  if (to.meta.require_auth) {
    try {
      const { data } = await http.get<User>("me");
      store.setUser(data);
      return next();
    } catch ({ response }: any) {
      return next({ name: "login" });
    }
  }
  next();
});

export default router;
