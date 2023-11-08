<template>
  <div class="table">
    <table>
      <caption>
        <div class="data">
          <div class="title">Grupos cadastrados</div>
        </div>
      </caption>
      <thead>
        <tr>
          <th>Nome</th>
          <th>Grupos</th>
          <th>Usuários</th>
          <th>Organizações</th>
          <th>Aplicações</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="row in store.list(Group)" :key="row.id">
          <td>{{ row.name }}</td>
          <td>
            <div class="permits">
              <div v-if="row.privileges?.group === 'rw'">
                <span class="material-icons">check</span>
                <span class="text">Escrita</span>
              </div>
              <div v-else-if="row.privileges?.group === 'r'">
                <span class="material-icons">check</span>
                <span class="text">Leitura</span>
              </div>
            </div>
          </td>
          <td>
            <div class="permits">
              <div v-if="row.privileges?.user === 'rw'">
                <span class="material-icons">check</span>
                <span class="text">Escrita</span>
              </div>
              <div v-else-if="row.privileges?.user === 'r'">
                <span class="material-icons">check</span>
                <span class="text">Leitura</span>
              </div>
            </div>
          </td>
          <td>
            <div class="permits">
              <div v-if="row.privileges?.client === 'rw'">
                <span class="material-icons">check</span>
                <span class="text">Escrita</span>
              </div>
              <div v-else-if="row.privileges?.client === 'r'">
                <span class="material-icons">check</span>
                <span class="text">Leitura</span>
              </div>
            </div>
          </td>
          <td>
            <div class="permits">
              <div v-if="row.privileges?.app === 'rw'">
                <span class="material-icons">check</span>
                <span class="text">Escrita</span>
              </div>
              <div v-else-if="row.privileges?.app === 'r'">
                <span class="material-icons">check</span>
                <span class="text">Leitura</span>
              </div>
            </div>
          </td>
          <td>
            <div class="buttons">
              <button @click="onEdit(row)" type="button" class="icon">
                <span class="material-icons">edit</span>
              </button>
              <button @click="onDelete(row)" type="button" class="icon">
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
import { Group } from '@/models';
import { useStore } from '@/store/store';
import { useRouter } from 'vue-router';
const router = useRouter();
const store = useStore();

const onEdit = (payload: Group) => {
  router.push({ name: 'group.edit', params: { id: payload.id } });
};

const onDelete = async (payload: Group) => {
  const result = await store.remove(payload.id, 'group');
  if (result.status === 204) {
    alert('Grupo removido com sucesso!');
  } else if (result.response?.data?.message) {
    alert(result.response.data.message);
  } else {
    alert('Erro ao remover grupo!');
    console.error(result.error);
  }
};
</script>
