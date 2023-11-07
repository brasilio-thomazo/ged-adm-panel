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
import { useRoute } from 'vue-router';

const emit = defineEmits<{ (e: 'update', app: App): void }>();
const props = defineProps<{ app: App }>();

const form = ref<CacheConfigRequest>({ custom: false, app_id: props.app.id });
const reply = ref<CacheConfigReply>({});
const store = useStore();
const route = useRoute();

const http = store.http();
async function save() {
  try {
    if (route.name === 'app.edit' && props.app.cache_config) {
      const url = `cache_config/${props.app.cache_config.id}`;
      const { data } = await http.put<CacheConfig>(url, form.value);
      emit('update', { ...props.app, cache_config: data });
    } else {
      const url = 'cache_config/';
      const { data } = await http.post<CacheConfig>(url, form.value);
      emit('update', { ...props.app, cache_config: data });
    }
  } catch (err: any) {
    if (err.response) {
      reply.value = err.response.data;
    } else {
      console.error(err.message);
    }
  }
}

if (route.name === 'app.edit' && props.app.cache_config) {
  form.value = {
    app_id: props.app.id,
    custom: props.app.cache_config.custom,
    driver: props.app.cache_config.driver,
    host: props.app.cache_config.host,
    port: props.app.cache_config.port,
  };
}
</script>
