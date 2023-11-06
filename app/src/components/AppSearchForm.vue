<template>
  <div v-if="loading" class="loading">aguarde carregando...</div>
  <form v-else @submit.prevent="onSubmit">
    <div class="form">
      <div class="line">
        <label for="s_name">Pesquisa:</label>
        <input type="text" id="s_name" v-model="form.name" required />
        <span v-if="error?.errors?.name" class="error">
          {{ error.errors.name.join(',') }}
        </span>
      </div>
      <div class="line">
        <fieldset class="group">
          <legend>Campos:</legend>
          <label v-for="(field, key) in tr" :for="key" class="box">
            <input
              type="checkbox"
              :id="key"
              :value="true"
              v-model="form.show_field[key]"
            />
            <span class="text">{{ field }}</span>
          </label>
        </fieldset>
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
import { useStore } from '@/store/store';
import { ref } from 'vue';

const request: ISearchRequest = {
  name: '',
  show_field: {
    department: true,
    document_type: true,
    comment: true,
    identity: true,
    name: true,
    storage: true,
    code: true,
  },
};

const tr = {
  department: 'Departamento',
  document_type: 'Tipo de documento',
  comment: 'Comentário',
  identity: 'Identidade',
  name: 'Nome',
  storage: 'Armazenamento',
  code: 'Código',
};

const form = ref<ISearchRequest>({ ...request });
const error = ref<ISearchReply>();
const router = useRouter();
const store = useStore();
const route = useRoute();

const http = store.http();
const loading = ref(true);
const emit = defineEmits<{
  (e: 'add', payload: ISearch): void;
  (e: 'update', payload: ISearch): void;
}>();

const onSubmit = async () => {
  try {
    if (route.name === 'app.search.edit') {
      const url = `app/${route.params.app}/search/${route.params.id}`;
      const { data } = await http.put<ISearch>(url, form.value);
      emit('update', data);
    } else {
      const url = `app/${route.params.app}/search`;
      const { data } = await http.post<ISearch>(url, form.value);
      emit('add', data);
    }
    router.push({
      name: 'app.search',
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

try {
  let url;
  if (route.name === 'app.search.edit') {
    url = `app/${route.params.app}/search/${route.params.id}`;
    const { data: _row } = await http.get<ISearch>(url);
    form.value = { ..._row };
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
</script>
