<template>
  <div class="table">
    <table>
      <thead>
        <tr>
          <th>Pesquisa</th>
          <th>Campos</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="row in rows">
          <td>{{ row.name }}</td>
          <td>{{ fields(row) }}</td>
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
import { useRoute, useRouter } from 'vue-router';
import { computed } from 'vue';
import _ from 'lodash';

const emit = defineEmits<{ (e: 'remove', id: number): void }>();
const props = defineProps<{ rows: ISearch[] }>();
const rows = computed(() => props.rows);
const router = useRouter();
const route = useRoute();
const store = useStore();

const http = store.http();

const tr = {
  department: 'Departamento',
  document_type: 'Tipo de documento',
  comment: 'Comentário',
  identity: 'Identidade',
  name: 'Nome',
  storage: 'Armazenamento',
  code: 'Código',
};

const fields = (payload: ISearch) => {
  const fields: string[] = [];
  _.map(payload.show_field, (value, key: keyof typeof tr) => {
    if (value) {
      fields.push(tr[key]);
    }
  });
  return fields.join(' | ');
};

const onEdit = (payload: ISearch) => {
  router.push({
    name: 'app.search.edit',
    params: {
      app: route.params.app,
      id: payload.id,
    },
  });
};

const onDelete = async (payload: ISearch) => {
  try {
    const msg = `Tem certeza que deseja remover a pesquisa ${payload.name}`;
    if (confirm(msg)) {
      const url = `app/${route.params.app}/search/${payload.id}`;
      await http.delete(url);
      emit('remove', payload.id);
    }
  } catch ({ response }: any) {}
};
</script>
