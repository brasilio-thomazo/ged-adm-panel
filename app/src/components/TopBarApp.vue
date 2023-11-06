<template>
  <div id="topbar">
    <ul>
      <li>
        <RouterLink :to="config['app'].url">
          <span class="text">{{ config['app'].label }}</span>
        </RouterLink>
      </li>
      <li v-if="route.name === 'app.new'">
        <span class="icon material-icons">double_arrow</span>
        <RouterLink :to="config['app.new'].url">
          <span class="text">{{ config['app.new'].label }}</span>
        </RouterLink>
      </li>
      <li v-if="route.name === 'app.edit'">
        <span class="icon material-icons">double_arrow</span>
        <RouterLink :to="config['app.edit'].url">
          <span class="text">{{ config['app.edit'].label }}</span>
        </RouterLink>
      </li>
      <li v-if="route.name === 'app.show'">
        <span class="icon material-icons">double_arrow</span>
        <RouterLink :to="config['app.show'].url">
          <span class="text">{{ config['app.show'].label }}</span>
        </RouterLink>
      </li>
      <li v-if="route.name === 'app.continue'">
        <span class="icon material-icons">double_arrow</span>
        <RouterLink :to="config['app.continue'].url">
          <span class="text">{{ config['app.continue'].label }}</span>
        </RouterLink>
      </li>
    </ul>
  </div>
</template>
<script lang="ts" setup>
import { useRoute } from 'vue-router';
const route = useRoute();

const config = {
  app: {
    url: '/app',
    label: 'Aplicações',
  },
  'app.new': {
    url: '/app/new',
    label: 'Nova aplicação',
  },
  'app.continue': {
    url: `/app/${route.params.id}/continue`,
    label: 'Continuando registro da aplicação',
  },
  'app.edit': {
    url: `/app/${route.params.id}/edit`,
    label: 'Editando aplicação',
  },
  'app.show': {
    url: `/app/${route.params.id}`,
    label: 'Visualizando aplicação',
  },
};
</script>
