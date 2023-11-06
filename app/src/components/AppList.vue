<template>
  <div v-if="loading" class="loading"></div>
  <div v-else class="table">
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
              <button @click="show(row)" type="button" class="icon">
                <span class="material-icons">pageview</span>
              </button>
              <button
                v-if="store.is_admin"
                @click="edit(row)"
                type="button"
                class="icon"
              >
                <span class="material-icons">edit</span>
              </button>
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

<script setup lang="ts">
import { useAppStore } from '@/store/app-store';
import { useStore } from '@/store/store';
import { useRouter } from 'vue-router';
import { intervalFromTime } from '@/provider';
import { ref } from 'vue';
const appStore = useAppStore();
const router = useRouter();
const loading = ref(true);
const store = useStore();

const http = store.http();

function edit(app: App) {
  router.push({ name: 'app.edit', params: { id: app.id } });
}

function show(app: App) {
  router.push({ name: 'app.show', params: { id: app.id } });
}

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
