<template>
  <div>
    <template v-if="loading">
      <Spinner />
    </template>
    <template v-else>
      <AppTopBar />
      <div class="view-content">
        <AppUserForm v-if="isForm" @add="add" @update="update" />
        <AppUserList v-else :rows="rows" @remove="remove">
          <div class="buttons">
            <button @click="create" type="button">
              <span class="material-icons">add</span>
              <span class="text">Novo usuário</span>
            </button>
          </div>
        </AppUserList>
      </div>
    </template>
  </div>
</template>

<script setup lang="ts">
import AppUserList from '@/components/AppUserList.vue';
import AppUserForm from '@/components/AppUserForm.vue';
import AppTopBar from '@/components/AppTopBar.vue';
import { useRoute, useRouter } from 'vue-router';
import Spinner from '@/components/Spinner.vue';
import { useStore } from '@/store/store';
import { computed, onMounted, ref } from 'vue';
import _ from 'lodash';

const route = useRoute();
const router = useRouter();
const store = useStore();
const http = store.http();

const rows = ref<IUser[]>([]);
const loading = ref(true);

const isForm = computed(() => {
  const name = route.name?.toString() || '';
  return /.*\.(new|edit)$/.test(name);
});

const create = () => {
  router.push({
    name: 'app.user.new',
    params: { app: route.params.app },
  });
};

const remove = (id: string) => {
  const index = _.findIndex(rows.value, { id });
  if (index > -1) {
    rows.value.splice(index, 1);
  }
};

const update = (payload: IUser) => {
  const index = _.findIndex(rows.value, { id: payload.id });
  if (index > -1) {
    rows.value.splice(index, 1, payload);
  }
};

const add = (payload: IUser) => {
  rows.value.unshift(payload);
};

onMounted(async () => {
  try {
    const url = `app/${route.params.app}/user`;
    const { data } = await http.get<IUser[]>(url);
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
