<template>
  <fieldset>
    <legend>Dados pessoais</legend>
    <form @submit.prevent="onSubmitProfile">
      <div class="form">
        <div class="line">
          <label for="name">Nome:</label>
          <input type="text" id="name" v-model="profile.name" required />
          <span v-if="profileReply?.errors?.name" class="error">
            {{ profileReply.errors.name.join(',') }}
          </span>
        </div>
        <div class="line">
          <label for="document">CPF/CNPJ:</label>
          <input
            type="tel"
            id="document"
            v-model="profile.identity"
            v-maska="identity"
            data-maska="['###.###.###-##', '##.###.###/####-##']"
            pattern="^[0-9]{2,3}\.[0-9]{3}\.[0-9]{3}((\/[0-9]{4})?)-[0-9]{2}$"
            required
          />
          <span v-if="profileReply?.errors?.identity" class="error">
            {{ profileReply.errors.identity.join(',') }}
          </span>
        </div>
        <div class="line">
          <label for="role">Cargo:</label>
          <input type="text" id="role" v-model="profile.role" required />
          <span v-if="profileReply?.errors?.role" class="error">
            {{ profileReply.errors.role.join(',') }}
          </span>
        </div>
        <div class="line">
          <label for="phone">Telefone:</label>
          <input
            type="tel"
            id="phone"
            v-model="profile.phone"
            v-maska="phone"
            data-maska="['(##) #####-####', '(##) ####-####']"
            pattern="^\([0-9]{2}\) (9?)[0-9]{4}-[0-9]{4}$"
            required
          />
          <span v-if="profileReply?.errors?.phone" class="error">
            {{ profileReply.errors.phone.join(',') }}
          </span>
        </div>
        <div class="line">
          <label for="email">E-mail:</label>
          <input type="email" id="email" v-model="profile.email" />
          <span v-if="profileReply?.errors?.email" class="error">
            {{ profileReply.errors.email.join(',') }}
          </span>
        </div>
        <div v-if="profileReply.message" class="line">
          <span class="error">{{ profileReply.message }}</span>
        </div>
        <div class="buttons">
          <button type="submit">
            <span class="material-icons">save</span>
            <span class="text">Salvar</span>
          </button>
        </div>
      </div>
    </form>
  </fieldset>
  <fieldset>
    <legend>Alterar senha</legend>
    <form @submit.prevent="onSubmitPassword">
      <div class="form">
        <div class="line">
          <label for="current_password">Senha atual:</label>
          <input
            type="password"
            id="current_password"
            v-model="password.current_password"
          />
          <span v-if="passwordReply?.errors?.current_password" class="error">
            {{ passwordReply.errors.current_password.join(',') }}
          </span>
        </div>
        <div class="line">
          <label for="password">Nova senha:</label>
          <input type="password" id="password" v-model="password.password" />
          <span v-if="passwordReply?.errors?.password" class="error">
            {{ passwordReply.errors.password.join(',') }}
          </span>
        </div>
        <div class="line">
          <label for="password_confirmation">Confirme a senha:</label>
          <input
            type="password"
            id="password_confirmation"
            v-model="password.password_confirmation"
          />
          <span
            v-if="passwordReply.errors?.password_confirmation"
            class="error"
          >
            {{ passwordReply.errors.password_confirmation.join(',') }}
          </span>
        </div>
        <div v-if="passwordReply.message" class="line">
          <span class="error">{{ passwordReply.message }}</span>
        </div>
        <div class="buttons">
          <button type="submit">
            <span class="material-icons">save</span>
            <span class="text">Salvar</span>
          </button>
        </div>
      </div>
    </form>
  </fieldset>
</template>

<script setup lang="ts">
// import Spinner from '@/components/Spinner.vue';
import { vMaska, MaskaDetail } from 'maska';
import { useStore } from '@/store/store';
import { maskDetail } from '@/provider';
import { onMounted, ref } from 'vue';

const profileRequest: IProfileRequest = {
  name: '',
  identity: '',
  role: '',
  phone: '',
  email: '',
};

const passwordRequest: IPasswordRequest = {
  current_password: '',
  password: '',
  password_confirmation: '',
};

const identity = ref<MaskaDetail>({ ...maskDetail });
const phone = ref<MaskaDetail>({ ...maskDetail });
const store = useStore();
const http = store.http();
const profileReply = ref<IProfileReply>({});
const passwordReply = ref<IPasswordReply>({});
const profile = ref<IProfileRequest>({ ...profileRequest });
const password = ref<IPasswordRequest>({ ...passwordRequest });

const emit = defineEmits<{
  (e: 'add', payload: IUser): void;
  (e: 'update', payload: IUser): void;
}>();

const onSubmitProfile = async () => {
  profileReply.value = {};
  try {
    const request = {
      ...profile.value,
      identity: identity.value.unmasked,
      phone: phone.value.unmasked,
    };
    const { data } = await http.put<IUser>('me', request);
    store.setUser(data);
    alert('Dados alterados com sucesso!');
  } catch (err: any) {
    if (err.response) {
      profileReply.value = err.response.data;
    } else {
      profileReply.value = { message: err.message };
    }
  }
};

const onSubmitPassword = async () => {
  passwordReply.value = {};
  try {
    const { data } = await http.post<IUser>('me', password.value);
    store.setUser(data);
    password.value = { ...passwordRequest };
    alert('Senha alterada com sucesso!');
  } catch (err: any) {
    if (err.response) {
      passwordReply.value = err.response.data;
    } else {
      passwordReply.value = { message: err.message };
    }
  }
};

try {
  const { data } = await http.get<IUser>('me');
  profile.value = {
    name: data.name,
    identity: data.identity,
    role: data.role,
    phone: data.phone,
    email: data.email,
  };
} catch (err: any) {
  if (err.response) {
    profileReply.value = err.response.data;
  } else {
    profileReply.value = { message: err.message };
  }
}
onMounted(async () => {});
</script>
