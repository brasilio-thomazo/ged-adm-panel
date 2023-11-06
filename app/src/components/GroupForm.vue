<template>
  <div v-if="loading" class="loading">aguarde carregando...</div>
  <form v-else @submit.prevent="save">
    <div class="form">
      <div class="line">
        <label for="name">Nome:</label>
        <input type="text" id="name" v-model="form.name" />
        <span v-if="error?.errors?.name" class="error">
          {{ error.errors.name.join(',') }}
        </span>
      </div>
      <div class="line">
        <p>Privilégios:</p>
        <div class="groups">
          <fieldset class="group">
            <legend>Grupos:</legend>
            <label class="box" for="group_read">
              <input
                type="radio"
                name="group"
                value="r"
                v-model="form.privileges.group"
                id="group_read"
              />
              <span class="icon"></span>
              <span class="text">Leitura</span>
            </label>
            <label class="box" for="group_write">
              <input
                type="radio"
                name="group"
                value="rw"
                v-model="form.privileges.group"
                id="group_write"
              />
              <span class="icon"></span>
              <span class="text">Escrita</span>
            </label>
          </fieldset>
          <fieldset class="group">
            <legend>Usuários:</legend>
            <label class="box" for="user_read">
              <input
                type="radio"
                name="user"
                value="r"
                v-model="form.privileges.user"
                id="user_read"
              />
              <span class="text">Leitura</span>
            </label>
            <label class="box" for="user_write">
              <input
                type="radio"
                name="user"
                value="rw"
                v-model="form.privileges.user"
                id="user_write"
              />
              <span class="text">Escrita</span>
            </label>
          </fieldset>
          <fieldset class="group">
            <legend>Organizações:</legend>
            <label class="box" for="client_read">
              <input
                type="radio"
                name="client"
                value="r"
                v-model="form.privileges.client"
                id="client_read"
              />
              <span class="text">Leitura</span>
            </label>
            <label class="box" for="client_write">
              <input
                type="radio"
                name="client"
                value="rw"
                v-model="form.privileges.client"
                id="client_write"
              />
              <span class="text">Escrita</span>
            </label>
          </fieldset>
          <fieldset class="group">
            <legend>Aplicações:</legend>
            <label class="box" for="app_read">
              <input
                type="radio"
                name="app"
                value="r"
                v-model="form.privileges.app"
                id="app_read"
              />
              <span class="text">Leitura</span>
            </label>
            <label class="box" for="app_write">
              <input
                type="radio"
                name="app"
                value="rw"
                v-model="form.privileges.app"
                id="app_write"
              />
              <span class="text">Escrita</span>
            </label>
          </fieldset>
        </div>
        <span v-if="error?.errors?.privileges" class="error">
          {{ error.errors.privileges.join(',') }}
        </span>
      </div>
      <div v-if="error?.message" class="error">{{ error.message }}</div>
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
import { groupRequest as request } from '@/provider';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from '@/store/store';
import { ref } from 'vue';

const form = ref<GroupRequest>({ ...request });
const error = ref<GroupError>();
const router = useRouter();
const store = useStore();
const route = useRoute();

const http = store.http();
const loading = ref(true);
const emit = defineEmits<{
  (e: 'add', payload: Group): void;
  (e: 'update', payload: Group): void;
}>();

async function save() {
  try {
    if (route.name === 'group.edit') {
      const url = `group/${route.params.id}`;
      const { data } = await http.put<Group>(url, form.value);
      emit('update', data);
    } else {
      const { data } = await http.post<Group>('group', form.value);
      emit('add', data);
    }
    router.push({ name: 'group' });
  } catch (err: any) {
    if (err.response) {
      error.value = err.response.data;
    } else {
      error.value = { message: err.message };
    }
  }
}

try {
  if (route.name === 'group.edit') {
    const { data } = await http.get<Group>(`group/${route.params.id}`);
    form.value = { ...data };
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
