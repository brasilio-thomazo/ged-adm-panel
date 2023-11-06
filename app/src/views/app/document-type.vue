<template>
  <div class="views">
    <AppTopBar />
    <div v-if="loading" class="loading">aguarde carregando...</div>
    <div v-else class="view-content">
      <AppDocumentTypeForm v-if="isForm" @add="add" @update="update" />
      <AppDocumentTypeList v-else :rows="rows" @remove="remove">
        <div class="buttons">
          <button @click="create" type="button">
            <span class="material-icons">add</span>
            <span class="text">Novo tipo de documento</span>
          </button>
        </div>
      </AppDocumentTypeList>
    </div>
  </div>
</template>

<script setup lang="ts">
import AppDocumentTypeList from '@/components/AppDocumentTypeList.vue';
import AppDocumentTypeForm from '@/components/AppDocumentTypeForm.vue';
import AppTopBar from '@/components/AppTopBar.vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from '@/store/store';
import { computed, ref } from 'vue';
import _ from 'lodash';

const route = useRoute();
const router = useRouter();
const store = useStore();
const http = store.http();

const rows = ref<IDocumentType[]>([]);
const loading = ref(true);

const isForm = computed(() => {
  const name = route.name?.toString() || '';
  return /.*\.(new|edit)$/.test(name);
});

const create = () => {
  router.push({
    name: 'app.document-type.new',
    params: { app: route.params.app },
  });
};

const remove = (id: number) => {
  const index = _.findIndex(rows.value, { id });
  if (index > -1) {
    rows.value.splice(index, 1);
  }
};

const update = (payload: IDocumentType) => {
  const index = _.findIndex(rows.value, { id: payload.id });
  if (index > -1) {
    rows.value.splice(index, 1, payload);
  }
};

const add = (payload: IDocumentType) => {
  rows.value.unshift(payload);
};

try {
  const url = `app/${route.params.app}/document_type`;
  const { data } = await http.get<IDocumentType[]>(url);
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
