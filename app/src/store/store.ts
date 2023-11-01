import axios from "axios";
import { defineStore } from "pinia";
import { inject, ref } from "vue";
import _ from "lodash";

export const useStore = defineStore("main.store", () => {
  const user = ref<User>();
  const is_admin = ref<boolean>(false);

  function setUser(payload: User) {
    user.value = payload;
    is_admin.value = _.findIndex(payload.groups, { is_admin: true }) >= 0;
  }

  function setToken(payload: string) {
    localStorage.setItem("token", payload);
  }

  function http() {
    const http = inject("http", axios);
    const token = localStorage.getItem("token");
    http.defaults.headers.common["Authorization"] = `Bearer ${token}`;
    return http;
  }

  return { setUser, setToken, user, http, is_admin };
});
