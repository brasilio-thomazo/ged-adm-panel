<template>
  <div id="appbar">
    <ul>
      <li v-for="link in links">
        <RouterLink :to="link.to">
          <span class="material-icons">{{ link.icon }}</span>
          <span class="text">{{ link.text }}</span>
        </RouterLink>
      </li>
    </ul>
  </div>
</template>
<script lang="ts" setup>
import { computed } from 'vue';

const props = defineProps<{ app: App }>();
const app = computed(() => props.app);
const links = [
  {
    icon: 'group',
    text: 'grupos',
    to: `/app/${app.value?.id}/group`,
  },
  {
    icon: 'person',
    text: 'usuários',
    to: `/app/${app.value?.id}/user`,
  },
  {
    icon: 'article',
    text: 'tipos de documento',
    to: `/app/${app.value?.id}/document-type`,
  },
  {
    icon: 'badge',
    text: 'departamentos',
    to: `/app/${app.value?.id}/department`,
  },
  {
    icon: 'manage_search',
    text: 'pesquisas',
    to: `/app/${app.value?.id}/search`,
  },
];
</script>
