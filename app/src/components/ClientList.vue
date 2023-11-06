<template>
  <div class="table">
    <table>
      <thead>
        <tr>
          <th>Nome</th>
          <th>CPF/CNPJ</th>
          <th>Telefone</th>
          <th>E-mail</th>
          <th>Tipo</th>
          <th>Responsável</th>
          <th>Cargo</th>
          <th>Registro</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="row in rows">
          <td>{{ row.name }}</td>
          <td>{{ mask(row.identity) }}</td>
          <td>{{ mask(row.phone, true) }}</td>
          <td>{{ row.email }}</td>
          <td>{{ scope[row.scope] }}</td>
          <td>{{ row.manager }}</td>
          <td>{{ row.role }}</td>
          <td>{{ dateFormat(row.created_at) }}</td>
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
import { mask, dateFormat } from '@/provider';
import { useStore } from '@/store/store';
import { useRouter } from 'vue-router';
import { computed } from 'vue';
import _ from 'lodash';

const emit = defineEmits<{ (e: 'remove', id: string): void }>();
const props = defineProps<{ rows: Client[] }>();
const rows = computed(() => props.rows);
const router = useRouter();
const store = useStore();

const http = store.http();

const onEdit = (payload: Client) => {
  router.push({ name: 'client.edit', params: { id: payload.id } });
};

const scope = { client: 'Cliente', provider: 'Fornecedor' };

const onDelete = async (payload: Client) => {
  try {
    if (
      confirm(`Tem certeza que deseja remover a organização ${payload.name}`)
    ) {
      await http.delete(`client/${payload.id}`);
      emit('remove', payload.id);
    }
  } catch ({ response }: any) {}
};
</script>
