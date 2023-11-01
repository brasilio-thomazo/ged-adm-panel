<template>
  <div>
    <div class="user-view">
      <UserList @edit="edit" />
      <hr />
      <UserForm :data="current" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { useUserStore } from "@/store/user-store";
import { inject, ref } from "vue";
import axios from "axios";
import UserList from "@/components/UserList.vue";
import UserForm from "@/components/UserForm.vue";

const http = inject("http", axios);
const userStore = useUserStore();
const current = ref<User | null>(null);

const { data: users } = await http.get<User[]>("users");
userStore.setRows(users);

function edit(data: User) {
  current.value = data;
}
</script>
