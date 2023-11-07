<template>
  <div v-if="loading" class="loading">Carregando aguarde...</div>
  <form v-else @submit.prevent="onSubmit">
    <div class="form">
      <div class="line">
        <label for="name">Nome:</label>
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
        <label for="role">Cargo:</label>
        <input type="text" id="role" v-model="form.role" required />
        <span v-if="error?.errors?.role" class="error">
          {{ error.errors.role.join(',') }}
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
        <label for="username">Usuário:</label>
        <input type="text" id="username" v-model="form.username" />
        <span v-if="error?.errors?.username" class="error">
          {{ error.errors.username.join(',') }}
        </span>
      </div>
      <div class="line">
        <label for="password">Senha:</label>
        <input type="password" id="password" v-model="form.password" />
        <span v-if="error?.errors?.password" class="error">
          {{ error.errors.password.join(',') }}
        </span>
      </div>
      <div class="line">
        <label for="password_confirmation">Confirme a senha:</label>
        <input
          type="password"
          id="password_confirmation"
          v-model="form.password_confirmation"
        />
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
import { userRequest as request, maskDetail } from '@/provider';
import { useRoute, useRouter } from 'vue-router';
import { vMaska, MaskaDetail } from 'maska';
import { ref } from 'vue';
import { useStore } from '@/store/store';

const document = ref<MaskaDetail>({ ...maskDetail });
const phone = ref<MaskaDetail>({ ...maskDetail });
const form = ref<UserRequest>({ ...request });
const error = ref<UserError>();
const router = useRouter();
const route = useRoute();

const store = useStore();
const http = store.http();
const loading = ref(true);

const emit = defineEmits<{
  (e: 'add', payload: User): void;
  (e: 'update', payload: User): void;
}>();

const onSubmit = async () => {
  try {
    const request = {
      ...form.value,
      identity: document.value.unmasked,
      phone: phone.value.unmasked,
    };

    if (route.name === 'user.edit') {
      const url = `user/${route.params.id}`;
      const { data } = await http.put<User>(url, request);
      emit('update', data);
    } else {
      const { data } = await http.post<User>('user', request);
      emit('add', data);
    }
    router.push({ name: 'user' });
  } catch (err: any) {
    if (err.response) {
      error.value = err.response.data;
    } else {
      error.value = { message: err.message };
    }
  }
};

try {
  const { data } = await http.get<User>('me');
  form.value = {
    ...data,
    groups: data.groups.map((group) => group.id),
    password: '',
    password_confirmation: '',
  };
  loading.value = false;
} catch (err: any) {
  if (err.response) {
    error.value = err.response.data;
  } else {
    error.value = { message: err.message };
  }
}
</script>
