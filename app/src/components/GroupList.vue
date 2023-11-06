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
        <tr v-for="row in rows" :key="row.id">
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
import { useStore } from '@/store/store';
import { useRouter } from 'vue-router';
import { computed } from 'vue';
import _ from 'lodash';

const emit = defineEmits<{ (e: 'remove', id: number): void }>();
const props = defineProps<{ rows: Group[] }>();
const rows = computed(() => props.rows);
const router = useRouter();
const store = useStore();

const http = store.http();

const onEdit = (payload: Group) => {
  router.push({ name: 'group.edit', params: { id: payload.id } });
};

const onDelete = async (payload: Group) => {
  try {
    if (confirm(`Tem certeza que deseja remover o grupo ${payload.name}`)) {
      await http.delete(`group/${payload.id}`);
      emit('remove', payload.id);
    }
  } catch ({ response }: any) {}
};
</script>
