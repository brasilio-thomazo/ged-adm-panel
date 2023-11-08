<template>
  <div id="appbar">
    <ul>
      <li v-for="link in links">
        <RouterLink v-if="app.started_at && link.only_started" :to="link.to">
          <span class="material-icons">{{ link.icon }}</span>
          <span class="text">{{ link.text }}</span>
        </RouterLink>
        <RouterLink v-if="!link.only_started" :to="link.to">
          <span class="material-icons">{{ link.icon }}</span>
          <span class="text">{{ link.text }}</span>
        </RouterLink>
      </li>
    </ul>
  </div>
</template>
<script lang="ts" setup>
import { useStore } from '@/store/store';
const store = useStore();
const app = store.getCurrent<IApp>();
const links = [
  {
    icon: 'edit',
    text: 'editar',
    to: `/app/${app.id}/edit`,
    only_started: false,
  },
  {
    icon: 'group',
    text: 'grupos',
    to: `/app/${app.id}/group`,
    only_started: true,
  },
  {
    icon: 'person',
    text: 'usuários',
    to: `/app/${app.id}/user`,
    only_started: true,
  },
  {
    icon: 'article',
    text: 'tipos de documento',
    to: `/app/${app.id}/document-type`,
    only_started: true,
  },
  {
    icon: 'badge',
    text: 'departamentos',
    to: `/app/${app.id}/department`,
    only_started: true,
  },
  {
    icon: 'manage_search',
    text: 'pesquisas',
    to: `/app/${app.id}/search`,
    only_started: true,
  },
];
</script>
