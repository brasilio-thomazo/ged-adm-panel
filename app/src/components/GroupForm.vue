<template>
  <div id="group-edit">
    <form @submit.prevent="save">
      <div class="form">
        <div class="line">
          <label for="name">Nome:</label>
          <input type="text" id="name" v-model="form.name" />
          <span v-if="error?.errors?.name" class="error">
            {{ error.errors.name.join(",") }}
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
            {{ error.errors.privileges.join(",") }}
          </span>
        </div>
        <div v-if="error?.message" class="error">{{ error.message }}</div>
        <div class="buttons">
          <button @click="reset" type="button">
            <span class="material-icons">clear_all</span>
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
import { useGroupStore } from "@/store/group-store";
import { computed, inject, ref, watchEffect } from "vue";
import axios from "axios";

const props = defineProps<{ data: Group | null }>();
const id = ref<number | null>(null);
const request = { name: "", privileges: {} };
const init = computed(() => {
  if (props.data === null) return { ...request };
  return {
    name: props.data.name,
    privileges: {
      group: props.data.privileges.group,
      user: props.data.privileges.user,
      client: props.data.privileges.client,
      app: props.data.privileges.app,
    },
  };
});
const http = inject("http", axios);

const store = useGroupStore();

const error = ref<GroupError>();
const form = ref<GroupRequest>({ ...init.value });

watchEffect(() => {
  if (props.data !== null) {
    form.value = { ...init.value };
    id.value = props.data.id;
  }
});

async function save() {
  try {
    const request = { ...form.value };
    if (id.value !== null) {
      const { data } = await http.put<Group>(`groups/${id.value}`, request);
      store.edit(data);
      reset();
      return;
    }
    const { data } = await http.post<Group>("groups", request);
    store.create(data);
  } catch ({ response }: any) {
    console.log(response.data);
    error.value = response?.data;
  }
}

function reset() {
  form.value.privileges = {};
  form.value.name = "";
  id.value = null;
}
</script>
