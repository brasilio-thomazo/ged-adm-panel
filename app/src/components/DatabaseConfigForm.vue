<template>
  <form @submit.prevent="onSubmit">
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
import router from '@/router';

const route = useRoute();
const store = useStore();

const app = store.getCurrent<IApp>();

const reply = ref<IDatabaseReply>({});
const form = ref<IDatabaseRequest>({
  custom: false,
  database: '',
  app_id: app.id,
});

if (app.database_config) {
  form.value = { ...app.database_config };
}

async function onSubmit() {
  let result: IResult;
  if (route.name === 'app.edit') {
    const id = app.database_config?.id;
    result = await store.update(id, form.value, 'database_config');
  } else {
    result = await store.create(form.value, 'database_config');
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
</script>
