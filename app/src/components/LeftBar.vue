<template>
  <nav v-if="store.user" class="left-bar">
    <ul class="menu">
      <li
        v-for="nav in navs"
        class="brand"
        :key="nav.icon"
        :class="{ active: isActive(nav.name) }"
      >
        <RouterLink :to="nav.to">
          <span class="material-icons">{{ nav.icon }}</span>
          <span class="text">{{ nav.text }}</span>
        </RouterLink>
      </li>
    </ul>
    <div class="footer">
      <button type="button" @click="logout">
        <span class="material-icons">logout</span>
        <span class="text">sair</span>
      </button>
    </div>
  </nav>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import { useStore } from '@/store/store';
import { ref, watchEffect } from 'vue';

const store = useStore();
const route = useRoute();
const router = useRouter();
const name = ref(route.name?.toString() || 'home');
const username = ref('Minha conta');

const isActive = (payload: string) => {
  const re = new RegExp(`^${payload}(\..*|)`);
  return re.test(name.value);
};

const navs = ref([
  {
    icon: 'dashboard',
    text: 'ADM Panel v0.2.5',
    to: '/',
    name: 'home',
  },
  {
    icon: 'account_box',
    text: username,
    to: '/profile',
    name: 'profile',
  },
  {
    icon: 'group',
    text: 'Grupos',
    to: '/group',
    name: 'group',
  },
  {
    icon: 'person',
    text: 'Usuários',
    to: '/user',
    name: 'user',
  },
  {
    icon: 'work',
    text: 'Organizações',
    to: '/client',
    name: 'client',
  },
  {
    icon: 'account_tree',
    text: 'Aplicações',
    to: '/app',
    name: 'app',
  },
]);

watchEffect(() => {
  name.value = route.name?.toString() || 'home';
  if (store.user)
    username.value = store.user.name.split(' ')[0] || store.user.name;
});

const logout = () => {
  localStorage.clear();
  store.user = undefined;
  router.push({ name: 'login' });
};
</script>
