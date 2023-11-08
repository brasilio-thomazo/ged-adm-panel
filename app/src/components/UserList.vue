<template>
  <div class="table">
    <table>
      <caption>
        <div class="data">
          <div class="title">Usuários cadastrados</div>
        </div>
      </caption>
      <thead>
        <tr>
          <th>Nome</th>
          <th>Cargo</th>
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
          <td>{{ row.role }}</td>
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
import { mask } from '@/provider';
import _ from 'lodash';

const emit = defineEmits<{ (e: 'remove', id: string): void }>();
const props = defineProps<{ rows: IUser[] }>();
const rows = computed(() => props.rows);
const router = useRouter();
const store = useStore();

const http = store.http();

const onEdit = (payload: IUser) => {
  router.push({ name: 'user.edit', params: { id: payload.id } });
};

const onDelete = async (payload: IUser) => {
  try {
    if (
      confirm(`Tem certeza que deseja remover o usuário ${payload.username}`)
    ) {
      await http.delete(`user/${payload.id}`);
      emit('remove', payload.id);
    }
  } catch ({ response }: any) {}
};
</script>
