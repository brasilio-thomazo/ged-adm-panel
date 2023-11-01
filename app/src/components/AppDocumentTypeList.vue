<template>
  <div class="table">
    <table>
      <caption>
        <div class="data">
          <div class="title">Tipos de documento</div>
        </div>
      </caption>
      <thead>
        <tr>
          <th>Tipo do documento</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="row in store.documentTypes">
          <td>{{ row.name }}</td>
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
const store = useAppStore();
const http = inject("http", axios);

const emit = defineEmits<{
  (e: "edit", payload: AppDocumentType): void;
}>();

async function destroy(data: AppDocumentType) {
  try {
    if (
      confirm(`Tem certeza que deseja remover o tipo de documento ${data.name}`)
    ) {
      await http.delete(`user/${data.id}`);
      store.removeDocumentType(data);
    }
  } catch ({ response }: any) {}
}
</script>
