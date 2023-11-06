<template>
  <div v-if="loading" class="loading">Carregando aguarde...</div>
  <div v-else class="table">
    <table>
      <caption>
        <div class="data">
          <div class="title">Usuários cadastrados</div>
        </div>
      </caption>
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
        <tr v-for="row in users">
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
</template>

<script setup lang="ts">
import { mask } from '@/provider';
import { useStore } from '@/store/store';
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
const store = useStore();
const http = store.http();
const users = ref<AppUser[]>([]);
const loading = ref(true);
const router = useRouter();
const route = useRoute();

async function destroy(user: AppUser) {
  try {
    if (confirm(`Tem certeza que deseja remover o usuário ${user.username}`)) {
      await http.delete(`user/${user.id}`);
      // store.destroy(user);
    }
  } catch ({ response }: any) {}
}

const onEdit = (user: AppUser) => {
  router.push({
    name: 'app.user.edit',
    params: { app: route.params.app, id: user.id },
  });
};

const { data } = await http.get<AppUser[]>('user');
users.value = data;
loading.value = false;
</script>
