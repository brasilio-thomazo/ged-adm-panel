<template>
  <div>
    <TopBar path="app" />
    <div class="view-content">
      <template v-if="isFormNew">
        <AppShow v-if="app" :app="app" />
        <AppForm v-else @update="update" />
        <DatabaseConfigShow v-if="app && app.database_config" :app="app" />
        <DatabaseConfigForm v-else-if="app" :app="app" @update="update" />
        <CacheConfigShow v-if="app && app.cache_config" :app="app" />
        <CacheConfigForm v-else-if="app" :app="app" @update="update" />
      </template>
      <template v-else-if="route.name === 'app.edit'">
        <AppForm @update="update" />
        <DatabaseConfigForm v-if="app" :app="app" @update="update" />
        <CacheConfigForm v-if="app" :app="app" @update="update" />
      </template>
      <template class="show" v-else-if="route.name === 'app.show'">
        <AppShow v-if="app" :app="app" />
        <DatabaseConfigShow v-if="app && app.database_config" :app="app" />
        <CacheConfigShow v-if="app && app.cache_config" :app="app" />
      </template>
      <AppList v-else>
        <div class="buttons">
          <button @click="router.push({ name: 'app.new' })" type="button">
            <span class="material-icons">add</span>
            <span class="text">Nova aplicação</span>
          </button>
        </div>
      </AppList>
    </div>
  </div>
</template>

<script setup lang="ts">
import DatabaseConfigForm from '@/components/DatabaseConfigForm.vue';
import DatabaseConfigShow from '@/components/DatabaseConfigShow.vue';
import CacheConfigForm from '@/components/CacheConfigForm.vue';
import CacheConfigShow from '@/components/CacheConfigShow.vue';
import { useRoute, useRouter } from 'vue-router';
import { computed, ref, watchEffect } from 'vue';
import AppForm from '@/components/AppForm.vue';
import AppList from '@/components/AppList.vue';
import AppShow from '@/components/AppShow.vue';
import TopBar from '@/components/TopBar.vue';
import { useStore } from '@/store/store';

const app = ref<App>();

const router = useRouter();
const route = useRoute();
const store = useStore();
const http = store.http();

const isFormNew = computed(() => {
  return route.name === 'app.new' || route.name === 'app.continue';
});

watchEffect(async () => {
  if (route.name === 'app.new') {
    app.value = undefined;
  }

  if (route.name === 'app.show' || route.name === 'app.continue') {
    const url = `app/${route.params.id}`;
    const { data } = await http.get<App>(url);
    if (data.cache_config === null || data.database_config === null) {
      router.push({ name: 'app.continue', params: { id: data.id } });
    }
    app.value = data;
  }
});

function update(obj: App) {
  app.value = obj;
  if (route.name !== 'app.new' && route.name !== 'app.continue') return;
  if (obj.cache_config !== null && obj.database_config !== null) {
    router.push({ name: 'app' });
  }
}
</script>
