<template>
  <form @submit.prevent="save">
    <div class="form">
      <fieldset>
        <legend>Aplicação:</legend>
        <div class="line">
          <label for="client_id">Organização:</label>
          <select
            id="client_id"
            v-model="form.client_id"
            :disabled="route.name === 'app.edit'"
            required
          >
            <option
              v-for="client in clients"
              :value="client.id"
              :key="client.id"
            >
              {{ client.name }}
            </option>
          </select>
          <span v-if="reply?.errors?.client_id" class="error">
            {{ reply.errors.client_id.join(',') }}
          </span>
        </div>
        <div class="line">
          <label for="path">Path:</label>
          <input
            type="text"
            id="path"
            v-model="form.path"
            :disabled="route.name === 'app.edit'"
            required
          />
          <span v-if="reply?.errors?.path" class="error">
            {{ reply.errors.path.join(',') }}
          </span>
        </div>
        <div class="line">
          <label for="use_domain">Domínio:</label>
          <div class="items">
            <div class="item">
              <label class="box" for="use_domain">
                <input
                  type="checkbox"
                  name="use_domain"
                  v-model="form.use_domain"
                  id="use_domain"
                />
                <span class="text">Usar domínio</span>
              </label>
            </div>
          </div>
        </div>
        <div v-if="form.use_domain" class="line">
          <label for="domain">Domínio:</label>
          <input type="text" id="domain" v-model="form.domain" required />
          <span v-if="reply?.errors?.domain" class="error">
            {{ reply.errors.domain.join(',') }}
          </span>
        </div>
        <div class="buttons">
          <button type="submit">
            <span class="material-icons">save</span>
            <span class="text">Salvar</span>
          </button>
        </div>
      </fieldset>
    </div>
  </form>
</template>

<script setup lang="ts">
import { appRequest as request } from '@/provider';
import { useRoute } from 'vue-router';
import { useStore } from '@/store/store';
import { Client } from '@/models';
import { ref } from 'vue';

const store = useStore();

const form = ref<IAppRequest>({ ...request });
const reply = ref<IAppReply>();

const clients = store.list(Client);
const route = useRoute();

async function save() {
  let result: IResult = { status: 200 };
  if (route.name === 'app.edit') {
    result = await store.update(route.params.id, form.value, 'app');
  } else {
    result = await store.create(form.value, 'app');
  }

  if (result.status === 200) return;
  else if (result.status === 201) {
    store.setCurrent(result.response.data);
  } else {
    if (result.response?.data) {
      reply.value = result.response.data;
    } else {
      reply.value = { message: result.error.message };
    }
  }
}

if (route.name === 'app.edit') {
  form.value = { ...store.getCurrent<IApp>() };
}
</script>
