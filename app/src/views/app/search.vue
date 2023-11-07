<template>
  <div class="views">
    <template v-if="loading">
      <Spinner />
    </template>
    <template v-else>
      <AppTopBar />
      <div class="view-content">
        <AppSearchForm v-if="isForm" @add="add" @update="update" />
        <AppSearchList v-else :rows="rows" @remove="remove">
          <div class="buttons">
            <button @click="create" type="button">
              <span class="material-icons">add</span>
              <span class="text">Nova pesquisa</span>
            </button>
          </div>
        </AppSearchList>
      </div>
    </template>
  </div>
</template>

<script setup lang="ts">
import AppSearchForm from '@/components/AppSearchForm.vue';
import AppSearchList from '@/components/AppSearchList.vue';
import AppTopBar from '@/components/AppTopBar.vue';
import { useRoute, useRouter } from 'vue-router';
import { computed, onMounted, ref } from 'vue';
import Spinner from '@/components/Spinner.vue';
import { useStore } from '@/store/store';
import _ from 'lodash';

const route = useRoute();
const router = useRouter();
const store = useStore();
const http = store.http();

const rows = ref<ISearch[]>([]);
const loading = ref(true);

const isForm = computed(() => {
  const name = route.name?.toString() || '';
  return /.*\.(new|edit)$/.test(name);
});

const create = () => {
  router.push({
    name: 'app.search.new',
    params: { app: route.params.app },
  });
};

const remove = (id: number) => {
  const index = _.findIndex(rows.value, { id });
  if (index > -1) {
    rows.value.splice(index, 1);
  }
};

const update = (payload: ISearch) => {
  const index = _.findIndex(rows.value, { id: payload.id });
  if (index > -1) {
    rows.value.splice(index, 1, payload);
  }
};

const add = (payload: ISearch) => {
  rows.value.unshift(payload);
};

onMounted(async () => {
  try {
    const url = `app/${route.params.app}/search`;
    const { data } = await http.get<ISearch[]>(url);
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
