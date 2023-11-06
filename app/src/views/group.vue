<template>
  <div class="views">
    <TopBar path="group" />
    <div v-if="loading" class="loading">aguarde carregando...</div>
    <div v-else class="view-content">
      <GroupForm v-if="isForm" @add="add" @update="update" />
      <GroupList v-else :rows="rows" @remove="remove">
        <div class="buttons">
          <button @click="router.push({ name: 'group.new' })" type="button">
            <span class="material-icons">add</span>
            <span class="text">Novo grupo</span>
          </button>
        </div>
      </GroupList>
    </div>
  </div>
</template>

<script setup lang="ts">
import GroupList from '@/components/GroupList.vue';
import GroupForm from '@/components/GroupForm.vue';
import TopBar from '@/components/TopBar.vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from '@/store/store';
import { computed, ref } from 'vue';
import _ from 'lodash';

const route = useRoute();
const router = useRouter();
const store = useStore();
const http = store.http();

const rows = ref<Group[]>([]);
const loading = ref(true);

const isForm = computed(
  () => route.name === 'group.new' || route.name === 'group.edit'
);

const remove = (id: number) => {
  const index = _.findIndex(rows.value, { id });
  if (index > -1) {
    rows.value.splice(index, 1);
  }
};

const update = (payload: Group) => {
  const index = _.findIndex(rows.value, { id: payload.id });
  if (index > -1) {
    rows.value.splice(index, 1, payload);
  }
};

const add = (payload: Group) => {
  rows.value.unshift(payload);
};

try {
  const { data } = await http.get<Group[]>('group');
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
