<template>
  <div>
    <TopBar path="client" />
    <div v-if="loading" class="loading">aguarde carregando...</div>
    <div v-else class="view-content">
      <ClientForm v-if="isForm" @add="add" @update="update" />
      <ClientList v-else :rows="rows" @remove="remove">
        <div class="buttons">
          <button @click="router.push({ name: 'client.new' })" type="button">
            <span class="material-icons">add</span>
            <span class="text">Nova organização</span>
          </button>
        </div>
      </ClientList>
    </div>
  </div>
</template>

<script setup lang="ts">
import ClientList from '@/components/ClientList.vue';
import ClientForm from '@/components/ClientForm.vue';
import { useRoute, useRouter } from 'vue-router';
import TopBar from '@/components/TopBar.vue';
import { useStore } from '@/store/store';
import { computed, ref } from 'vue';
import _ from 'lodash';

const route = useRoute();
const router = useRouter();
const store = useStore();
const http = store.http();

const rows = ref<Client[]>([]);
const loading = ref(true);

const isForm = computed(
  () => route.name === 'client.new' || route.name === 'client.edit'
);

const remove = (id: string) => {
  const index = _.findIndex(rows.value, { id });
  if (index > -1) {
    rows.value.splice(index, 1);
  }
};

const update = (payload: Client) => {
  const index = _.findIndex(rows.value, { id: payload.id });
  if (index > -1) {
    rows.value.splice(index, 1, payload);
  }
};

const add = (payload: Client) => {
  rows.value.unshift(payload);
};

try {
  const { data } = await http.get<Client[]>('client');
  rows.value = data;
  loading.value = false;
} catch (err: any) {
  if (err.response) {
    console.log(err.response.data);
  } else {
    console.log(err.message);
  }
}
</script>
