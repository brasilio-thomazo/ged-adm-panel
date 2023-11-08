<template>
  <form @submit.prevent="onSubmit">
    <div class="form">
      <div class="line">
        <label for="name">Nome:</label>
        <input type="text" id="name" v-model="form.name" />
        <span v-if="reply?.errors?.name" class="error">
          {{ reply.errors.name.join(',') }}
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
        <span v-if="reply?.errors?.privileges" class="error">
          {{ reply.errors.privileges.join(',') }}
        </span>
      </div>
      <div v-if="reply?.message" class="error">{{ reply.message }}</div>
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
import { Group } from '@/models';

const form = ref<IGroupRequest>({ ...request });
const reply = ref<IGroupReply>();
const router = useRouter();
const store = useStore();
const route = useRoute();

if (route.name === 'group.edit') {
  form.value = { ...store.getCurrent<Group>() };
}

const onSubmit = async () => {
  let result: IResult = { status: 200 };

  if (route.name === 'group.edit') {
    result = await store.update(route.params.id, form.value, 'group');
  } else {
    result = await store.create(form.value, 'group');
  }

  if (result.status === 200 || result.status === 201)
    router.push({ name: 'group' });
  else {
    if (result.response?.data) {
      reply.value = result.response.data;
    } else {
      reply.value = { message: result.error.message };
    }
  }
};
</script>
