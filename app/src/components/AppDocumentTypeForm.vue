<template>
  <Spinner v-if="loading" />
  <form v-else @submit.prevent="onSubmit">
    <div class="form">
      <div class="line">
        <label for="name">Tipo de documento:</label>
        <input type="text" id="name" v-model="form.name" required />
        <span v-if="error?.errors?.name" class="error">
          {{ error.errors.name.join(',') }}
        </span>
      </div>
      <div v-if="error?.message" class="line">
        <span class="error">{{ error.message }}</span>
      </div>
      <div class="buttons">
        <button type="submit">
          <span class="material-icons">save</span>
          <span class="text">Salvar</span>
        </button>
      </div>
    </div>
  </form>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import Spinner from '@/components/Spinner.vue';
import { useStore } from '@/store/store';
import { onMounted, ref } from 'vue';

const form = ref<IDocumentTypeRequest>({ name: '' });
const error = ref<IDocumentTypeReply>();
const router = useRouter();
const store = useStore();
const route = useRoute();

const http = store.http();
const loading = ref(true);
const emit = defineEmits<{
  (e: 'add', payload: IDocumentType): void;
  (e: 'update', payload: IDocumentType): void;
}>();

const onSubmit = async () => {
  try {
    if (route.name === 'app.document-type.edit') {
      const url = `app/${route.params.app}/document_type/${route.params.id}`;
      const { data } = await http.put<IDocumentType>(url, form.value);
      emit('update', data);
    } else {
      const url = `app/${route.params.app}/document_type`;
      const { data } = await http.post<IDocumentType>(url, form.value);
      emit('add', data);
    }
    router.push({
      name: 'app.document-type',
      params: { app: route.params.app },
    });
  } catch (err: any) {
    if (err.response) {
      error.value = err.response.data;
    } else {
      error.value = { message: err.message };
    }
  }
};

onMounted(async () => {
  try {
    let url;
    if (route.name === 'app.document-type.edit') {
      url = `app/${route.params.app}/document_type/${route.params.id}`;
      const { data: _documentTypes } = await http.get<IDocumentType>(url);
      form.value = { ..._documentTypes };
    }
  } catch (err: any) {
    if (err.response) {
      error.value = err.response.data;
    } else {
      error.value = { message: err.message };
    }
  } finally {
    loading.value = false;
  }
});
</script>
