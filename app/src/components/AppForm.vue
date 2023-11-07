<template>
  <Spinner v-if="loading" />
  <form v-else @submit.prevent="save">
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
import { useRoute, useRouter } from 'vue-router';
import Spinner from '@/components/Spinner.vue';
import { useStore } from '@/store/store';
import { ref } from 'vue';

const emit = defineEmits<{ (e: 'update', app: App): void }>();
const form = ref<AppRequest>({ ...request });
const clients = ref<Client[]>([]);
const reply = ref<AppReply>();
const router = useRouter();
const loading = ref(true);
const store = useStore();
const route = useRoute();

const http = store.http();

async function save() {
  try {
    if (route.name === 'app.edit') {
      const url = `app/${route.params.id}`;
      const { data } = await http.put<App>(url, form.value);
      emit('update', data);
    } else {
      const { data } = await http.post<App>('app', form.value);
      emit('update', data);
    }
  } catch (err: any) {
    if (err.response) {
      reply.value = err.response.data;
    } else {
      reply.value = { message: err.message };
      console.error(err.message);
    }
  }
}

if (route.name === 'app.edit') {
  try {
    const { data } = await http.get<App>(`app/${route.params.id}`);
    form.value = {
      application: data.application,
      use_domain: data.use_domain,
      domain: data.domain,
      path: data.path,
      client_id: data.client_id,
      use_s3: data.use_s3,
      aws_region: data.aws_region,
      aws_bucket: data.aws_bucket,
    };
    emit('update', data);
  } catch (err: any) {
    console.error(err.message);
    router.push({ name: 'not_found' });
  }
}

try {
  const { data } = await http.get<Client[]>('client');
  clients.value = data;
  loading.value = false;
} catch (err: any) {
  console.error(err.message);
}
</script>
