<template>
  <form @submit.prevent="save">
    <div class="form">
      <fieldset>
        <legend>Cache</legend>
        <div class="line">
          <div class="items">
            <div class="item">
              <label for="cache_custom" class="box">
                <input
                  type="checkbox"
                  id="cache_custom"
                  v-model="form.custom"
                />
                <span class="text">Customizar</span>
              </label>
            </div>
          </div>
        </div>
        <div v-if="form.custom" class="line">
          <label for="driver">Driver:</label>
          <select id="driver" v-model="form.driver">
            <option v-for="driver in drivers" :value="driver.value">
              {{ driver.text }}
            </option>
          </select>
          <div v-if="reply.errors?.driver" class="error">
            {{ reply.errors.driver.join(';') }}
          </div>
        </div>
        <div v-if="form.custom" class="line">
          <label for="host">Serivdor:</label>
          <input type="text" id="host" v-model="form.host" />
          <div v-if="reply.errors?.host" class="error">
            {{ reply.errors.host.join(';') }}
          </div>
        </div>
        <div v-if="form.custom" class="line">
          <label for="port">Porta</label>
          <input type="number" id="port" v-model="form.port" />
          <div v-if="reply.errors?.port" class="error">
            {{ reply.errors.port.join(';') }}
          </div>
        </div>
        <div class="buttons">
          <button type="submit" class="button">
            <span class="material-icons">save</span>
            <span class="text">Salvar</span>
          </button>
        </div>
      </fieldset>
    </div>
  </form>
</template>
<script lang="ts" setup>
import { ref } from 'vue';
import { cache_drivers as drivers } from '@/provider';
import { useStore } from '@/store/store';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const store = useStore();
const router = useRouter();
const app = store.getCurrent<IApp>();

const form = ref<ICacheRequest>({ custom: false, app_id: app.id });
const reply = ref<ICacheReply>({});

async function save() {
  let result: IResult;
  if (route.name === 'app.edit') {
    const id = app.cache_config?.id;
    result = await store.update(id, form.value, 'cache_config');
  } else {
    result = await store.create(form.value, 'cache_config');
  }
  if (result.status === 200) {
    return;
  } else if (result.status === 201 && app.cache_config && app.database_config) {
    router.push({ name: 'app.show', params: { id: app.id } });
  } else {
    if (result.response?.data) {
      reply.value = result.response.data;
    } else {
      reply.value = { message: result.error.message };
    }
  }
}

if (route.name === 'app.edit') {
  if (app.cache_config) {
    form.value = { ...app.cache_config };
  }
}
</script>
