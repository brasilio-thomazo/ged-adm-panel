<template>
  <div id="user-edit">
    <form @submit.prevent="save">
      <div class="form">
        <div class="line">
          <label for="name">Tipo:</label>
          <input type="text" id="name" v-model="form.name" required />
          <span v-if="error?.errors?.name" class="error">
            {{ error.errors.name.join(",") }}
          </span>
        </div>
        <div class="buttons">
          <button type="button" @click="reset">
            <span class="material-icons">clear</span>
            <span class="text">Limpar</span>
          </button>
          <button type="submit">
            <span class="material-icons">save</span>
            <span class="text">Salvar</span>
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { inject, ref, watchEffect } from "vue";
import axios from "axios";
import { useAppStore } from "@/store/app-store";

const props = defineProps<{ data: AppUser | null; id: any }>();
const emit = defineEmits<{ (e: "close"): void }>();
const request: AppDocumentTypeRequest = {
  name: "",
};

const http = inject("http", axios);
const appStore = useAppStore();
const error = ref<UserError>();
const form = ref<AppDocumentTypeRequest>({ ...request });
const id = ref<string | null>(null);

watchEffect(() => {
  if (props.data !== null) {
    form.value = {
      name: props.data.name,
    };
    id.value = props.data.id;
  }
});

async function save() {
  try {
    const request = { ...form.value };

    if (id.value !== null) {
      const url = `app/${props.id}/document_type/${id.value}`;
      const { data } = await http.put<AppDocumentType>(url, request);
      appStore.updateDocumentTypes(data);
    } else {
      const url = `app/${props.id}/document_type`;
      const { data } = await http.post<AppDocumentType>(url, request);
      appStore.addDocumentType(data);
    }
    reset();
  } catch ({ response }: any) {
    console.log(response.data);
    error.value = response?.data;
  }
}

function reset() {
  form.value = { ...request };
  id.value = null;
}
</script>
