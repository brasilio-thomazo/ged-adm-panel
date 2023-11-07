<template>
  <div>
    <template v-if="loading">
      <Spinner />
    </template>
    <template v-else>
      <AppTopBar />
      <div class="view-content">
        <AppGroupForm v-if="isForm" @add="add" @update="update" />
        <AppGroupList v-else :rows="rows" @remove="remove">
          <div class="buttons">
            <button @click="create" type="button">
              <span class="material-icons">add</span>
              <span class="text">Novo grupo</span>
            </button>
          </div>
        </AppGroupList>
      </div>
    </template>
  </div>
</template>

<script setup lang="ts">
import AppGroupList from '@/components/AppGroupList.vue';
import AppGroupForm from '@/components/AppGroupForm.vue';
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

const rows = ref<IGroup[]>([]);
const loading = ref(true);

const isForm = computed(() => {
  const name = route.name?.toString() || '';
  return /.*\.(new|edit)$/.test(name);
});

const create = () => {
  router.push({
    name: 'app.group.new',
    params: { app: route.params.app },
  });
};

const remove = (id: number) => {
  const index = _.findIndex(rows.value, { id });
  if (index > -1) {
    rows.value.splice(index, 1);
  }
};

const update = (payload: IGroup) => {
  const index = _.findIndex(rows.value, { id: payload.id });
  if (index > -1) {
    rows.value.splice(index, 1, payload);
  }
};

const add = (payload: IGroup) => {
  rows.value.unshift(payload);
};

onMounted(async () => {
  try {
    const url = `app/${route.params.app}/group`;
    const { data } = await http.get<IGroup[]>(url);
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
