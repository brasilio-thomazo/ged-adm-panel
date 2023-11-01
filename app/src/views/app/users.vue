<template>
  <div>
    <div class="user-view">
      <AppUserList @edit="edit" />
      <hr />
      <AppUserForm :data="current" :id="id" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { inject, ref } from "vue";
import axios from "axios";
import { useAppStore } from "@/store/app-store";
import { useRoute } from "vue-router";
import AppUserList from "@/components/AppUserList.vue";
import AppUserForm from "@/components/AppUserForm.vue";

const http = inject("http", axios);
const appStore = useAppStore();
const current = ref<AppUser | null>(null);
const route = useRoute();

const id = route.params.id;

const { data: users } = await http.get<AppUser[]>(`app/${id}/users`);
const { data: groups } = await http.get<AppGroup[]>(`app/${id}/groups`);
const { data: departments } = await http.get<AppDepartment[]>(
  `app/${id}/departments`
);
appStore.setUsers(users);
appStore.setDepartments(departments);
appStore.setGroups(groups);

function edit(data: AppUser) {
  current.value = data;
}
</script>
