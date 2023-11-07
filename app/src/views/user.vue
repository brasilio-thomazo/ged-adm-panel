<template>
  <div>
    <template v-if="loading">
      <Spinner />
    </template>
    <template v-else>
      <TopBar path="user" />
      <div class="view-content">
        <UserForm v-if="isForm" @add="add" @update="update" />
        <UserList v-else :rows="users" @remove="remove">
          <div class="buttons">
            <button @click="router.push({ name: 'user.new' })" type="button">
              <span class="material-icons">add</span>
              <span class="text">Novo usuário</span>
            </button>
          </div>
        </UserList>
      </div>
    </template>
  </div>
</template>

<script setup lang="ts">
import UserList from '@/components/UserList.vue';
import UserForm from '@/components/UserForm.vue';
import { useRoute, useRouter } from 'vue-router';
import Spinner from '@/components/Spinner.vue';
import { computed, onMounted, ref } from 'vue';
import TopBar from '@/components/TopBar.vue';
import { useStore } from '@/store/store';
import _ from 'lodash';

const route = useRoute();
const router = useRouter();
const store = useStore();
const http = store.http();

const users = ref<User[]>([]);
const loading = ref(true);
const isForm = computed(
  () => route.name === 'user.new' || route.name === 'user.edit'
);

const remove = (id: string) => {
  const index = _.findIndex(users.value, { id });
  if (index > -1) {
    users.value.splice(index, 1);
  }
};

const update = (user: User) => {
  const index = _.findIndex(users.value, { id: user.id });
  if (index > -1) {
    users.value.splice(index, 1, user);
  }
};

const add = (user: User) => {
  users.value.unshift(user);
};

onMounted(async () => {
  try {
    const { data } = await http.get<User[]>('user');
    users.value = data;
  } catch (err: any) {
    console.error(err);
  }
  loading.value = false;
});
</script>
