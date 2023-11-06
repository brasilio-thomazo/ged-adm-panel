<template>
  <nav v-if="store.user" class="left-bar">
    <div class="brand">
      <RouterLink to="/">ADM Panel</RouterLink>
    </div>
    <ul class="menu">
      <li>
        <RouterLink to="/group">Grupos</RouterLink>
      </li>
      <li>
        <RouterLink to="/user">Usuários</RouterLink>
      </li>
      <li>
        <RouterLink to="/client">Organizações</RouterLink>
      </li>
      <li>
        <RouterLink to="/app">Aplicações</RouterLink>
      </li>
    </ul>
  </nav>
</template>
<script setup lang="ts">
import { useStore } from '@/store/store';
const store = useStore();
</script>
