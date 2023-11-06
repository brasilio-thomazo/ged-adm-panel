<template>
  <div v-if="loading" class="loading">aguarde carregando...</div>
  <form v-else @submit.prevent="onSubmit">
    <div class="form">
      <div class="line">
        <label for="name">Razão social:</label>
        <input type="text" id="name" v-model="form.name" required />
        <span v-if="error?.errors?.name" class="error">
          {{ error.errors.name.join(',') }}
        </span>
      </div>
      <div class="line">
        <label for="document">CPF/CNPJ:</label>
        <input
          type="tel"
          id="document"
          v-model="form.identity"
          v-maska="document"
          data-maska="['###.###.###-##', '##.###.###/####-##']"
          pattern="^[0-9]{2,3}\.[0-9]{3}\.[0-9]{3}((\/[0-9]{4})?)-[0-9]{2}$"
          required
        />
        <span v-if="error?.errors?.identity" class="error">
          {{ error.errors.identity.join(',') }}
        </span>
      </div>
      <div class="line">
        <label for="phone">Telefone:</label>
        <input
          type="tel"
          id="phone"
          v-model="form.phone"
          v-maska="phone"
          data-maska="['(##) #####-####', '(##) ####-####']"
          pattern="^\([0-9]{2}\) (9?)[0-9]{4}-[0-9]{4}$"
          required
        />
        <span v-if="error?.errors?.phone" class="error">
          {{ error.errors.phone.join(',') }}
        </span>
      </div>
      <div class="line">
        <label for="email">E-mail:</label>
        <input type="email" id="email" v-model="form.email" />
        <span v-if="error?.errors?.email" class="error">
          {{ error.errors.email.join(',') }}
        </span>
      </div>
      <div class="line">
        <label for="group">Tipo:</label>
        <div class="items">
          <div class="item">
            <label class="box" for="scope_client">
              <input
                type="radio"
                name="scope"
                v-model="form.scope"
                value="client"
                id="scope_client"
              />
              <span class="text">Cliente</span>
            </label>
          </div>
          <div class="item">
            <label class="box" for="scope_agent">
              <input
                type="radio"
                name="scope"
                v-model="form.scope"
                value="provider"
                id="scope_agent"
                disabled
              />
              <span class="text">Distribuidor</span>
            </label>
          </div>
        </div>
        <span v-if="error?.errors?.scope" class="error">
          {{ error.errors.scope.join(',') }}
        </span>
      </div>
      <div class="line">
        <label for="manager">Responsável:</label>
        <input type="text" id="manager" v-model="form.manager" />
        <span v-if="error?.errors?.manager" class="error">
          {{ error.errors.manager.join(',') }}
        </span>
      </div>
      <div class="line">
        <label for="role">Cargo:</label>
        <input type="text" id="role" v-model="form.role" required />
        <span v-if="error?.errors?.role" class="error">
          {{ error.errors.role.join(',') }}
        </span>
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
import { maskDetail, clientRequest as request } from '@/provider';
import { useRoute, useRouter } from 'vue-router';
import { vMaska, MaskaDetail } from 'maska';
import { useStore } from '@/store/store';
import { ref } from 'vue';

const document = ref<MaskaDetail>({ ...maskDetail });
const phone = ref<MaskaDetail>({ ...maskDetail });
const form = ref<ClientRequest>({ ...request });
const error = ref<ClientError>();
const router = useRouter();
const store = useStore();
const route = useRoute();

const emit = defineEmits<{
  (e: 'add', payload: Client): void;
  (e: 'update', payload: Client): void;
}>();

const http = store.http();
const loading = ref(true);

const onSubmit = async () => {
  try {
    const request = {
      ...form.value,
      identity: document.value.unmasked,
      phone: phone.value.unmasked,
    };

    if (route.name === 'client.edit') {
      const url = `client/${route.params.id}`;
      const { data } = await http.put<Client>(url, request);
      emit('update', data);
    } else {
      const { data } = await http.post<Client>('client', request);
      emit('add', data);
    }
    router.push({ name: 'client' });
  } catch (ex: any) {
    if (ex.response) {
      error.value = ex.response.data;
    } else {
      error.value = { message: ex.message };
    }
  }
};

if (route.name === 'client.edit') {
  try {
    const { data } = await http.get<Client>(`client/${route.params.id}`);
    form.value = { ...data };
    loading.value = false;
  } catch (ex: any) {
    router.push({ name: 'not_found' });
  }
} else loading.value = false;
</script>
