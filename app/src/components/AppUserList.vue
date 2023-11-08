<template>
  <div class="table">
    <table>
      <thead>
        <tr>
          <th>Nome</th>
          <th>Departamento</th>
          <th>Documento</th>
          <th>Telefone</th>
          <th>E-mail</th>
          <th>Usuário</th>
          <th>Grupos</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="row in rows">
          <td>{{ row.name }}</td>
          <td>{{ row.department.name }}</td>
          <td>{{ mask(row.identity) }}</td>
          <td>{{ mask(row.phone, true) }}</td>
          <td>{{ row.email }}</td>
          <td>{{ row.username }}</td>
          <td>{{ row.groups.map((g) => g.name).join(' | ') }}</td>
          <td>
            <div class="buttons">
              <button @click="onEdit(row)" type="button" class="icon">
                <span class="material-icons">edit</span>
              </button>
              <button @click="destroy(row)" type="button" class="icon">
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
import { mask } from '@/provider';
import { useStore } from '@/store/store';
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const emit = defineEmits<{ (e: 'remove', id: string): void }>();
const props = defineProps<{ rows: IAppUser[] }>();
const rows = computed(() => props.rows);
const store = useStore();
const http = store.http();
const router = useRouter();
const route = useRoute();

async function destroy(user: IAppUser) {
  try {
    const msg = `Tem certeza que deseja remover o usuário ${user.username}`;
    if (confirm(msg)) {
      await http.delete(`user/${user.id}`);
      emit('remove', user.id);
    }
  } catch ({ response }: any) {}
}

const onEdit = (user: IAppUser) => {
  router.push({
    name: 'app.user.edit',
    params: { app: route.params.app, id: user.id },
  });
};
</script>
