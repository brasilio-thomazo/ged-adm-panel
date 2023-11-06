<template>
  <div class="table">
    <table>
      <thead>
        <tr>
          <th>Departamento</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="row in rows">
          <td>{{ row.name }}</td>
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
const props = defineProps<{ rows: IDepartment[] }>();
const rows = computed(() => props.rows);
const router = useRouter();
const route = useRoute();
const store = useStore();

const http = store.http();

const onEdit = (payload: IDepartment) => {
  router.push({
    name: 'app.department.edit',
    params: {
      app: route.params.app,
      id: payload.id,
    },
  });
};

const onDelete = async (payload: IDepartment) => {
  try {
    const msg = `Tem certeza que deseja remover o departamento ${payload.name}`;
    if (confirm(msg)) {
      const url = `app/${route.params.app}/department/${payload.id}`;
      await http.delete(url);
      emit('remove', payload.id);
    }
  } catch ({ response }: any) {}
};
</script>
