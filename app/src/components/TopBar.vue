<template>
  <div id="topbar">
    <ul>
      <li>
        <RouterLink :to="config.home.url">
          <span class="text">{{ config.home.label }}</span>
        </RouterLink>
      </li>
      <li v-if="route.name === config.new.name">
        <span class="icon material-icons">double_arrow</span>
        <RouterLink :to="config.new.url">
          <span class="text">{{ config.new.label }}</span>
        </RouterLink>
      </li>
      <li v-if="route.name === config.edit.name">
        <span class="icon material-icons">double_arrow</span>
        <RouterLink :to="config.edit.url">
          <span class="text">{{ config.edit.label }}</span>
        </RouterLink>
      </li>
      <li v-if="route.name === config.show.name">
        <span class="icon material-icons">double_arrow</span>
        <RouterLink :to="config.show.url">
          <span class="text">{{ config.show.label }}</span>
        </RouterLink>
      </li>
    </ul>
  </div>
</template>
<script lang="ts" setup>
import { useRoute } from 'vue-router';
const props = defineProps<{ path: 'user' | 'group' | 'client' }>();
const route = useRoute();

const configs = {
  user: {
    home: {
      url: '/user',
      label: 'Usuários',
      name: 'user',
    },
    new: {
      url: '/user/new',
      label: 'Novo usuário',
      name: 'user.new',
    },
    edit: {
      url: `/user/${route.params.id}/edit`,
      label: 'Editando usuário',
      name: 'user.edit',
    },
    show: {
      url: `/user/${route.params.id}`,
      label: 'Visualizando usuário',
      name: 'user.show',
    },
  },
  group: {
    home: {
      url: '/group',
      label: 'Grupos',
      name: 'group',
    },
    new: {
      url: '/group/new',
      label: 'Novo grupo',
      name: 'group.new',
    },
    edit: {
      url: `/group/${route.params.id}/edit`,
      label: 'Editando grupo',
      name: 'group.edit',
    },
    show: {
      url: `/group/${route.params.id}`,
      label: 'Visualizando grupo',
      name: 'group.show',
    },
  },
  client: {
    home: {
      url: '/client',
      label: 'Organizações',
      name: 'client',
    },
    new: {
      url: '/client/new',
      label: 'Nova organização',
      name: 'client.new',
    },
    edit: {
      url: `/client/${route.params.id}/edit`,
      label: 'Editando organização',
      name: 'client.edit',
    },
    show: {
      url: `/client/${route.params.id}`,
      label: 'Visualizando organização',
      name: 'client.show',
    },
  },
};

const config = configs[props.path];
</script>
