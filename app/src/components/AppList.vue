<template>
  <template v-if="loading">
    <div class="loading">aguarde carregando...</div>
  </template>
  <template v-else>
    <div class="table">
      <table>
        <thead>
          <tr>
            <th>Organização</th>
            <th>Tipo</th>
            <th>Path</th>
            <th>URL</th>
            <th>Banco de dados</th>
            <th>Uptime/Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="row in appStore.rows" :key="row.id">
            <td>{{ row.client.name }}</td>
            <td class="upper">
              {{ row.application === 0 ? 'cliente' : 'distribuidor' }}
            </td>
            <td>{{ row.path }}</td>
            <td class="lower">http://{{ row.subdomain }}</td>
            <td class="lower">
              {{ row.database_config?.database }}
            </td>
            <td>
              <div class="status">
                <span class="uptime">
                  {{ intervalFromTime(row.started_at) }}
                </span>
              </div>
            </td>
            <td>
              <div class="buttons">
                <a :href="row.url_kubernetes" class="icon" target="_blank">
                  <span class="material-icons">widgets</span>
                </a>
                <RouterLink
                  :to="`/app/${row.id}/group`"
                  class="icon"
                  title="Grupos"
                >
                  <span class="material-icons">group</span>
                </RouterLink>
                <RouterLink
                  :to="`/app/${row.id}/user`"
                  class="icon"
                  title="Usuários"
                >
                  <span class="material-icons">person</span>
                </RouterLink>
                <RouterLink
                  :to="`/app/${row.id}/document-type`"
                  class="icon"
                  title="Tipos de documento"
                >
                  <span class="material-icons">article</span>
                </RouterLink>
                <RouterLink
                  :to="`/app/${row.id}/department`"
                  class="icon"
                  title="Departamentos"
                >
                  <span class="material-icons">badge</span>
                </RouterLink>
                <RouterLink
                  :to="`/app/${row.id}/search`"
                  class="icon"
                  title="Pesquisas"
                >
                  <span class="material-icons">manage_search</span>
                </RouterLink>
                <RouterLink
                  :to="`/app/${row.id}`"
                  class="icon"
                  title="Visualizar"
                >
                  <span class="material-icons">file_open</span>
                </RouterLink>
                <RouterLink
                  :to="`/app/${row.id}/edit`"
                  class="icon"
                  title="Editar"
                >
                  <span class="material-icons">edit</span>
                </RouterLink>
                <button
                  v-if="store.is_admin"
                  @click="destroy(row)"
                  type="button"
                  class="icon"
                >
                  <span class="material-icons">delete</span>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <slot />
  </template>
</template>

<script setup lang="ts">
import { useAppStore } from '@/store/app-store';
import { intervalFromTime } from '@/provider';
import { useStore } from '@/store/store';
import { ref } from 'vue';
const appStore = useAppStore();
const loading = ref(true);
const store = useStore();

const http = store.http();

async function destroy(app: App) {
  try {
    if (confirm(`Tem certeza que deseja remover a applicação ${app.id}?`)) {
      await http.delete(`app/${app.id}`);
      appStore.remove(app);
    }
  } catch ({ response }: any) {}
}

try {
  const { data } = await http.get<App[]>('app');
  appStore.setRows(data);
} catch (ex: any) {
  console.error(ex.message);
} finally {
  loading.value = false;
}
</script>
