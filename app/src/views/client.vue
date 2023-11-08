<template>
  <div>
    <template v-if="loading">
      <Spinner />
    </template>
    <template v-else>
      <TopBar path="client" />
      <div class="view-content">
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
    </template>
  </div>
</template>

<script setup lang="ts">
import ClientList from '@/components/ClientList.vue';
import ClientForm from '@/components/ClientForm.vue';
import { useRoute, useRouter } from 'vue-router';
import { computed, onMounted, ref } from 'vue';
import Spinner from '@/components/Spinner.vue';
import TopBar from '@/components/TopBar.vue';
import { useStore } from '@/store/store';
import _ from 'lodash';

const route = useRoute();
const router = useRouter();
const store = useStore();
const http = store.http();

const rows = ref<IClient[]>([]);
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

const update = (payload: IClient) => {
  const index = _.findIndex(rows.value, { id: payload.id });
  if (index > -1) {
    rows.value.splice(index, 1, payload);
  }
};

const add = (payload: IClient) => {
  rows.value.unshift(payload);
};

onMounted(async () => {
  try {
    const { data } = await http.get<IClient[]>('client');
    rows.value = data;
  } catch (err: any) {
    if (err.response) {
      console.log(err.response.data);
    } else {
      console.log(err.message);
    }
  } finally {
    loading.value = false;
  }
});
</script>
