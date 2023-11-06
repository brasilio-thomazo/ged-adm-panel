<template>
  <form @submit.prevent="save">
    <div class="form">
      <fieldset>
        <legend>Banco de dados</legend>
        <div class="line">
          <div class="items">
            <div class="item">
              <label for="db_custom" class="box">
                <input type="checkbox" id="db_custom" v-model="form.custom" />
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
          <label for="write_host">Serivdor master</label>
          <input type="text" id="write_host" v-model="form.writer_host" />
          <div v-if="reply.errors?.writer_host" class="error">
            {{ reply.errors.writer_host.join(';') }}
          </div>
        </div>
        <div v-if="form.custom" class="line">
          <label for="write_port">Porta master</label>
          <input type="number" id="write_port" v-model="form.writer_port" />
          <div v-if="reply.errors?.writer_port" class="error">
            {{ reply.errors.writer_port.join(';') }}
          </div>
        </div>
        <div v-if="form.custom" class="line">
          <label for="read_host">Servidor slave</label>
          <input type="text" id="read_host" v-model="form.reader_host" />
          <div v-if="reply.errors?.reader_host" class="error">
            {{ reply.errors.reader_host.join(';') }}
          </div>
        </div>
        <div v-if="form.custom" class="line">
          <label for="read_port">Porta slave</label>
          <input type="number" id="read_port" v-model="form.reader_port" />
          <div v-if="reply.errors?.reader_port" class="error">
            {{ reply.errors.reader_port.join(';') }}
          </div>
        </div>
        <div class="line">
          <label for="database">Nome do banco</label>
          <input type="text" id="database" v-model="form.database" />
          <div v-if="reply.errors?.database" class="error">
            {{ reply.errors.database.join(';') }}
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
import { db_drivers as drivers } from '@/provider';
import { useRoute } from 'vue-router';
import { useStore } from '@/store/store';

const emit = defineEmits<{ (e: 'update', app: App): void }>();
const props = defineProps<{ app: App }>();
const reply = ref<DatabaseConfigReply>({});
const form = ref<DatabaseConfigRequest>({
  custom: false,
  database: '',
  app_id: props.app.id,
});
const route = useRoute();
const store = useStore();
const http = store.http();

if (props.app.database_config) {
  form.value = {
    app_id: props.app.id,
    custom: props.app.database_config.custom,
    database: props.app.database_config.database,
    driver: props.app.database_config.driver,
    reader_host: props.app.database_config.reader_host,
    reader_port: props.app.database_config.reader_port,
    writer_host: props.app.database_config.writer_host,
    writer_port: props.app.database_config.writer_port,
  };
}

async function save() {
  try {
    if (route.name === 'app.edit' && props.app.database_config) {
      const url = `database_config/${props.app.database_config.id}`;
      const { data } = await http.put<DatabaseConfig>(url, form.value);
      emit('update', { ...props.app, database_config: data });
    } else {
      const url = 'database_config';
      const { data } = await http.post<DatabaseConfig>(url, form.value);
      emit('update', { ...props.app, database_config: data });
    }
  } catch (err: any) {
    if (err.response) {
      reply.value = err.response.data;
    } else {
      console.error(err);
    }
  }
}
</script>
