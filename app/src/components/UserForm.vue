<template>
  <div id="user-edit">
    <form @submit.prevent="save">
      <div class="form">
        <div class="line">
          <label for="name">Nome:</label>
          <input type="text" id="name" v-model="form.name" required />
          <span v-if="error?.errors?.name" class="error">
            {{ error.errors.name.join(",") }}
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
            {{ error.errors.identity.join(",") }}
          </span>
        </div>
        <div class="line">
          <label for="role">Cargo:</label>
          <input type="text" id="role" v-model="form.role" required />
          <span v-if="error?.errors?.role" class="error">
            {{ error.errors.role.join(",") }}
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
            {{ error.errors.phone.join(",") }}
          </span>
        </div>
        <div class="line">
          <label for="email">E-mail:</label>
          <input type="email" id="email" v-model="form.email" />
          <span v-if="error?.errors?.email" class="error">
            {{ error.errors.email.join(",") }}
          </span>
        </div>
        <div class="line">
          <label for="username">Usuário:</label>
          <input type="text" id="username" v-model="form.username" />
          <span v-if="error?.errors?.username" class="error">
            {{ error.errors.username.join(",") }}
          </span>
        </div>
        <div class="line">
          <label for="password">Senha:</label>
          <input type="password" id="password" v-model="form.password" />
          <span v-if="error?.errors?.password" class="error">
            {{ error.errors.password.join(",") }}
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
            <div class="item" v-for="group in groupStore.rows">
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
          <span v-if="error?.errors?.groups" class="error">
            {{ error.errors.groups.join(",") }}
          </span>
        </div>
        <div class="buttons">
          <button type="button" @click="reset">
            <span class="material-icons">clear</span>
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
import { useUserStore } from "@/store/user-store";
import { inject, ref, watchEffect } from "vue";
import { vMaska, MaskaDetail } from "maska";
import axios from "axios";

const props = defineProps<{ data: User | null }>();
const emit = defineEmits<{ (e: "close"): void }>();
const id = ref<string | null>(null);
const userRequest: UserRequest = {
  name: "",
  identity: "",
  role: "",
  phone: "",
  email: "",
  username: "",
  groups: [],
  password: "",
  password_confirmation: "",
};

const maskDetail = {
  masked: "",
  unmasked: "",
  completed: false,
};

const http = inject("http", axios);
const groupStore = useGroupStore();
const store = useUserStore();
const error = ref<UserError>();
const form = ref<UserRequest>({ ...userRequest });
const document = ref<MaskaDetail>({ ...maskDetail });
const phone = ref<MaskaDetail>({ ...maskDetail });

watchEffect(() => {
  if (props.data !== null) {
    form.value = {
      name: props.data.name,
      identity: props.data.identity,
      role: props.data.role,
      phone: props.data.phone,
      email: props.data.email,
      username: props.data.username,
      groups: props.data.groups.map((g) => g.id),
      password: "",
      password_confirmation: "",
    };
    id.value = props.data.id;
  }
});

async function save() {
  try {
    const request = {
      ...form.value,
      document: document.value.unmasked,
      phone: phone.value.unmasked,
    };

    if (id.value !== null) {
      const { data } = await http.put<User>(`users/${id.value}`, request);
      store.edit(data);
    } else {
      const { data } = await http.post<User>("users", request);
      store.create(data);
    }
    reset();
  } catch ({ response }: any) {
    console.log(response.data);
    error.value = response?.data;
  }
}

function reset() {
  form.value = { ...userRequest };
  id.value = null;
}

const { data: groups } = await http.get<Group[]>("groups");
groupStore.setRows(groups);
</script>