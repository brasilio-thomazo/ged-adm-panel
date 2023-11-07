<template>
  <Spinner v-if="loading" />
  <form v-else @submit.prevent="onSubmit">
    <div class="form">
      <div class="line">
        <label for="name">Nome:</label>
        <input type="text" id="name" v-model="form.name" />
        <span v-if="error?.errors?.name" class="error">
          {{ error.errors.name.join(',') }}
        </span>
      </div>
      <div class="line">
        <p class="label">Privilégios:</p>
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
            <legend>Documentos:</legend>
            <label class="box" for="document_reader">
              <input
                type="radio"
                name="app"
                value="r"
                v-model="form.privileges.document"
                id="document_reader"
              />
              <span class="text">Leitura</span>
            </label>
            <label class="box" for="document_writer">
              <input
                type="radio"
                name="app"
                value="rw"
                v-model="form.privileges.document"
                id="document_writer"
              />
              <span class="text">Escrita</span>
            </label>
          </fieldset>
        </div>
        <span v-if="error?.errors?.privileges" class="error">
          {{ error.errors.privileges.join(',') }}
        </span>
      </div>
      <div v-if="form.privileges.document" class="line">
        <label for="type_0">Tipos de documento:</label>
        <div class="items">
          <div class="item">
            <label class="box" for="type_0">
              <input
                type="checkbox"
                :value="0"
                v-model="form.types"
                id="type_0"
              />
              <span class="text">Todos</span>
            </label>
          </div>
          <div v-for="documentType in documentTypes" class="item">
            <label class="box" :for="`type_${documentType.id}`">
              <input
                type="checkbox"
                :value="documentType.id"
                v-model="form.types"
                :id="`type_${documentType.id}`"
                :disabled="form.types.indexOf(0) >= 0"
              />
              <span class="text">{{ documentType.name }}</span>
            </label>
          </div>
        </div>
        <span v-if="error?.errors?.types" class="error">
          {{ error.errors.types.join(',') }}
        </span>
      </div>
      <div v-if="form.privileges.document" class="line">
        <label for="department_0">Departamentos:</label>
        <div class="items">
          <div class="item">
            <label class="box" for="department_0">
              <input
                type="checkbox"
                :value="0"
                v-model="form.departments"
                id="department_0"
              />
              <span class="text">Todos</span>
            </label>
          </div>
          <div v-for="department in departments" class="item">
            <label class="box" :for="`department_${department.id}`">
              <input
                type="checkbox"
                :value="department.id"
                v-model="form.departments"
                :id="`department_${department.id}`"
                :disabled="form.departments.indexOf(0) >= 0"
              />
              <span class="text">{{ department.name }}</span>
            </label>
          </div>
        </div>
        <span v-if="error?.errors?.departments" class="error">
          {{ error.errors.departments.join(',') }}
        </span>
      </div>
      <div v-if="form.privileges.document" class="line">
        <label for="search_0">Pesquisas:</label>
        <div class="items">
          <div class="item">
            <label class="box" for="search_0">
              <input
                type="checkbox"
                :value="0"
                v-model="form.searches"
                id="search_0"
              />
              <span class="text">Todos</span>
            </label>
          </div>
          <div v-for="search in searches" class="item">
            <label class="box" :for="`search_${search.id}`">
              <input
                type="checkbox"
                :value="search.id"
                v-model="form.searches"
                :id="`search_${search.id}`"
                :disabled="form.searches.indexOf(0) >= 0"
              />
              <span class="text">{{ search.name }}</span>
            </label>
          </div>
        </div>
        <span v-if="error?.errors?.searches" class="error">
          {{ error.errors.searches.join(',') }}
        </span>
      </div>
      <div v-if="form.privileges.document" class="line">
        <label for="documents">Personalizados:</label>
        <div class="items">
          <div class="item">
            <select name="" id="documents" v-model="rule.documents">
              <option v-for="(text, key) in rules" :value="key">
                DOCUMENTO.{{ text.toLocaleUpperCase() }}
              </option>
            </select>
          </div>
          <span class="material-icons">equal</span>
          <div class="item">
            <select name="" id="users" v-model="rule.users">
              <option v-for="(value, key) in rules" :value="key">
                USUÁRIO.{{ value.toLocaleUpperCase() }}
              </option>
            </select>
          </div>
          <button @click="pushRule" type="button">
            <span class="material-icons">add</span>
          </button>
        </div>
        <div class="custom-rules">
          <div class="rule" v-for="value in form.custom">
            <span class="text"> documento.{{ rules[value.documents] }} </span>
            <span class="material-icons">arrow_forward</span>
            <span class="text">usuário.{{ rules[value.users] }}</span>
          </div>
        </div>
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
import { appGroupRequest as request } from '@/provider';
import { useRoute, useRouter } from 'vue-router';
import Spinner from '@/components/Spinner.vue';
import { useStore } from '@/store/store';
import { ref } from 'vue';

const form = ref<IGroupRequest>({ ...request });
const error = ref<IGroupReply>();
const router = useRouter();
const store = useStore();
const route = useRoute();

const http = store.http();
const loading = ref(true);
const emit = defineEmits<{
  (e: 'add', payload: IGroup): void;
  (e: 'update', payload: IGroup): void;
}>();

const documentTypes = ref<IDocumentType[]>([]);
const departments = ref<IDepartment[]>([]);
const searches = ref<ISearch[]>([]);
const rule = ref<IRule>({ documents: 'identity', users: 'identity' });

const rules = {
  department_id: 'departamento',
  identity: 'identidade',
  name: 'nome',
};

const pushRule = () => {
  form.value.custom.push({ ...rule.value });
};

const onSubmit = async () => {
  try {
    if (route.name === 'app.group.edit') {
      const url = `app/${route.params.app}/group/${route.params.id}`;
      const { data } = await http.put<IGroup>(url, form.value);
      emit('update', data);
    } else {
      const url = `app/${route.params.app}/group`;
      const { data } = await http.post<IGroup>(url, form.value);
      emit('add', data);
    }
    router.push({ name: 'app.group', params: { app: route.params.app } });
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
  if (route.name === 'app.group.edit') {
    url = `app/${route.params.app}/group/${route.params.id}`;
    const { data: _group } = await http.get<IGroup>(url);
    form.value = { ..._group };
  }

  url = `app/${route.params.app}/document_type`;
  const { data: _types } = await http.get<IDocumentType[]>(url);
  documentTypes.value = _types;

  url = `app/${route.params.app}/department`;
  const { data: _departments } = await http.get<IDepartment[]>(url);
  departments.value = _departments;

  url = `app/${route.params.app}/search`;
  const { data: _searches } = await http.get<ISearch[]>(url);
  searches.value = _searches;
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
