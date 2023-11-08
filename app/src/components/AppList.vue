<template>
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
        <tr v-for="row in store.list(App)" :key="row.id">
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

<script setup lang="ts">
import { App } from '@/models';
import { intervalFromTime } from '@/provider';
import { useStore } from '@/store/store';

const store = useStore();

async function destroy(app: IApp) {
  const result = await store.remove(app.id, 'app');
  if (result.status === 204) {
    alert('Aplicação removida com sucesso!');
  } else if (result.status === 401) {
    alert('Você não tem permissão para remover esta aplicação!');
  } else {
    alert('Não foi possível remover a aplicação!');
  }
}
</script>
