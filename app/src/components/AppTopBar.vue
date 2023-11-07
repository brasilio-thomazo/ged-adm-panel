<template>
  <div id="topbar">
    <ul>
      <li>
        <RouterLink to="/app">
          <span class="text">applicações</span>
        </RouterLink>
      </li>
      <li>
        <RouterLink :to="`/app/${route.params.app}`">
          <span class="text">{{ path }}</span>
        </RouterLink>
      </li>
      <li v-for="nav in list">
        <RouterLink :to="nav.url">
          <span class="text">{{ nav.label }}</span>
        </RouterLink>
      </li>
    </ul>
  </div>
</template>
<script lang="ts" setup>
import { useStore } from '@/store/store';
import { computed, ref } from 'vue';
import { useRoute } from 'vue-router';

type Navigation = {
  url: string;
  label: string;
};

const route = useRoute();
const store = useStore();
const path = ref('');
const http = store.http();

const list = computed(() => getNavigation());

const getNavigation = () => {
  const links: Navigation[] = [];
  const name = route.name?.toString() || '';
  let data = { path: '', label: '' };

  if (/app\.group(\..*|)/.test(name)) data = { path: 'group', label: 'grupos' };
  else if (/app\.user(\..*|)/.test(name))
    data = { path: 'user', label: 'usuários' };
  else if (/app\.document-type(\..*|)/.test(name))
    data = { path: 'document-type', label: 'tipos de documento' };
  else if (/app\.department(\..*|)/.test(name))
    data = { path: 'department', label: 'departamentos' };
  else if (/app\.search(\..*|)/.test(name))
    data = { path: 'search', label: 'pesquisas' };

  const base = `/app/${route.params.app}/${data.path}`;

  links.push({ url: base, label: data.label });

  if (/.*\.new$/.test(name))
    links.push({ url: `${base}/new`, label: 'cadastro' });
  if (/.*\.edit$/.test(name))
    links.push({ url: `${base}/${route.params.id}/edit`, label: 'edição' });
  return links;
};

const { data } = await http.get<App>(`app/${route.params.app}`);
path.value = data.path;
</script>
