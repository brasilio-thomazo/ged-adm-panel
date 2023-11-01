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
        <tr v-for="row in store.users">
          <td>{{ row.name }}</td>
          <td>{{ row.department.name }}</td>
          <td>{{ mask(row.identity) }}</td>
          <td>{{ mask(row.phone, true) }}</td>
          <td>{{ row.email }}</td>
          <td>{{ row.username }}</td>
          <td>{{ row.groups.map((g) => g.name).join(" | ") }}</td>
          <td>
            <div class="buttons">
              <button @click="emit('edit', row)" type="button" class="icon">
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
import { useAppStore } from "@store/app-store";
import axios from "axios";
import { inject } from "vue";
import { mask } from "@/provider";
const store = useAppStore();
const http = inject("http", axios);

const emit = defineEmits<{
  (e: "edit", payload: AppUser): void;
}>();

async function destroy(user: AppUser) {
  try {
    if (confirm(`Tem certeza que deseja remover o usuário ${user.username}`)) {
      await http.delete(`user/${user.id}`);
      // store.destroy(user);
    }
  } catch ({ response }: any) {}
}
</script>
