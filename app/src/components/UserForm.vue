<template>
  <Spinner v-if="loading" />
  <form v-else @submit.prevent="onSubmit">
    <div class="form">
      <div class="line">
        <label for="name">Nome:</label>
        <input type="text" id="name" v-model="form.name" required />
        <span v-if="reply?.errors?.name" class="error">
          {{ reply.errors.name.join(',') }}
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
        <span v-if="reply?.errors?.identity" class="error">
          {{ reply.errors.identity.join(',') }}
        </span>
      </div>
      <div class="line">
        <label for="role">Cargo:</label>
        <input type="text" id="role" v-model="form.role" required />
        <span v-if="reply?.errors?.role" class="error">
          {{ reply.errors.role.join(',') }}
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
        <span v-if="reply?.errors?.phone" class="error">
          {{ reply.errors.phone.join(',') }}
        </span>
      </div>
      <div class="line">
        <label for="email">E-mail:</label>
        <input type="email" id="email" v-model="form.email" />
        <span v-if="reply?.errors?.email" class="error">
          {{ reply.errors.email.join(',') }}
        </span>
      </div>
      <div class="line">
        <label for="username">Usuário:</label>
        <input type="text" id="username" v-model="form.username" />
        <span v-if="reply?.errors?.username" class="error">
          {{ reply.errors.username.join(',') }}
        </span>
      </div>
      <div class="line">
        <label for="password">Senha:</label>
        <input type="password" id="password" v-model="form.password" />
        <span v-if="reply?.errors?.password" class="error">
          {{ reply.errors.password.join(',') }}
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
      <div class="line">
        <label for="group">Grupos:</label>
        <div class="items">
          <div class="item" v-for="group in groups">
            <label class="box" :for="`group_${group.id}`">
              <input
                type="checkbox"
                v-model="form.groups"
                :value="group.id"
                :id="`group_${group.id}`"
              />
              <span class="icon"></span>
              <span class="text">{{ group.name }}</span>
            </label>
          </div>
        </div>
        <span v-if="reply?.errors?.groups" class="error">
          {{ reply.errors.groups.join(',') }}
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
import { userRequest as request, maskDetail } from '@/provider';
import { useRoute, useRouter } from 'vue-router';
import Spinner from '@/components/Spinner.vue';
import { vMaska, MaskaDetail } from 'maska';
import { useStore } from '@/store/store';
import { onMounted, ref } from 'vue';

const document = ref<MaskaDetail>({ ...maskDetail });
const phone = ref<MaskaDetail>({ ...maskDetail });
const form = ref<IUserRequest>({ ...request });
const reply = ref<IUserReply>();
const router = useRouter();
const route = useRoute();

const store = useStore();
const http = store.http();

const groups = ref<IGroup[]>([]);
const loading = ref(true);

const emit = defineEmits<{
  (e: 'add', payload: IUser): void;
  (e: 'update', payload: IUser): void;
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
      const { data } = await http.put<IUser>(url, request);
      emit('update', data);
    } else {
      const { data } = await http.post<IUser>('user', request);
      emit('add', data);
    }
    router.push({ name: 'user' });
  } catch (err: any) {
    if (err.response) {
      reply.value = err.response.data;
    } else {
      reply.value = { message: err.message };
    }
  }
};

onMounted(async () => {
  try {
    const { data } = await http.get<IGroup[]>('group');
    groups.value = data;
    if (route.name === 'user.edit') {
      const { data } = await http.get<IUser>(`user/${route.params.id}`);
      form.value = {
        ...data,
        groups: data.groups.map((group) => group.id),
        password: '',
        password_confirmation: '',
      };
    }
  } catch (err: any) {
    if (err.response) {
      reply.value = err.response.data;
    } else {
      reply.value = { message: err.message };
    }
  } finally {
    loading.value = false;
  }
});
</script>
