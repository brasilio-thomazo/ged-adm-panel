import { useStore } from "@/store/store";
import axios from "axios";
import { inject } from "vue";
import { RouteRecordRaw, createRouter, createWebHashHistory } from "vue-router";

const routes: RouteRecordRaw[] = [
  {
    name: "login",
    path: "/login",
    component: () => import("@/views/login.vue"),
    meta: { require_auth: false },
  },
  {
    name: "not_found",
    path: "/:pathMatch(.*)*",
    component: () => import("@/views/404.vue"),
    meta: { require_auth: false },
  },
  {
    name: "home",
    path: "/",
    component: () => import("@/views/home.vue"),
    meta: { require_auth: true },
  },
  {
    name: "user",
    path: "/user",
    component: () => import("@/views/user.vue"),
    meta: { require_auth: true },
  },
  {
    name: "user.new",
    path: "/user/new",
    component: () => import("@/views/user.vue"),
    meta: { require_auth: true },
  },
  {
    name: "user.edit",
    path: "/user/:id/edit",
    component: () => import("@/views/user.vue"),
    meta: { require_auth: true },
  },
  {
    name: "group",
    path: "/group",
    component: () => import("@/views/group.vue"),
    meta: { require_auth: true },
  },
  {
    name: "group.new",
    path: "/group/new",
    component: () => import("@/views/group.vue"),
    meta: { require_auth: true },
  },
  {
    name: "group.edit",
    path: "/group/:id/edit",
    component: () => import("@/views/group.vue"),
    meta: { require_auth: true },
  },
  {
    name: "client",
    path: "/client",
    component: () => import("@/views/client.vue"),
    meta: { require_auth: true },
  },
  {
    name: "client.new",
    path: "/client/new",
    component: () => import("@/views/client.vue"),
    meta: { require_auth: true },
  },
  {
    name: "client.edit",
    path: "/client/:id/edit",
    component: () => import("@/views/client.vue"),
    meta: { require_auth: true },
  },
  {
    name: "app",
    path: "/app",
    component: () => import("@/views/app.vue"),
    meta: { require_auth: true },
  },
  {
    name: "app.new",
    path: "/app/new",
    component: () => import("@/views/app.vue"),
    meta: { require_auth: true },
  },
  {
    name: "app.continue",
    path: "/app/:id/continue",
    component: () => import("@/views/app.vue"),
    meta: { require_auth: true },
  },
  {
    name: "app.edit",
    path: "/app/:id/edit",
    component: () => import("@/views/app.vue"),
    meta: { require_auth: true },
  },
  {
    name: "app.show",
    path: "/app/:id",
    component: () => import("@/views/app.vue"),
    meta: { require_auth: true },
  },
  {
    name: "app.users",
    path: "/app/:id/users",
    component: () => import("@/views/app/users.vue"),
    meta: { require_auth: true },
  },
  {
    name: "profile",
    path: "/profile",
    component: () => import("@/views/profile.vue"),
    meta: { require_auth: true },
  },
  {
    name: "app.users",
    path: "/app/:id/users",
    component: () => import("@/views/app/users.vue"),
    meta: { require_auth: true },
  },
  {
    name: "app.document-types",
    path: "/app/:id/document_types",
    component: () => import("@/views/app/document-types.vue"),
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
  const token = localStorage.getItem("token");
  http.defaults.headers.common["Authorization"] = `Bearer ${token}`;
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
