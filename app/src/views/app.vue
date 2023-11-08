<template>
  <div>
    <TopBar path="app" />
    <div class="view-content">
      <template v-if="isFormNew">
        <AppShow v-if="app" />
        <AppForm v-else />
        <DatabaseConfigShow v-if="app?.database_config" />
        <DatabaseConfigForm v-else-if="app" />
        <CacheConfigShow v-if="app?.cache_config" />
        <CacheConfigForm v-else-if="app" />
      </template>
      <template v-else-if="route.name === 'app.edit'">
        <AppForm />
        <DatabaseConfigForm />
        <CacheConfigForm />
      </template>
      <template class="show" v-else-if="route.name === 'app.show'">
        <AppShow />
        <DatabaseConfigShow />
        <CacheConfigShow />
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
import { computed } from 'vue';
import AppForm from '@/components/AppForm.vue';
import AppList from '@/components/AppList.vue';
import AppShow from '@/components/AppShow.vue';
import TopBar from '@/components/TopBar.vue';
import { useStore } from '@/store/store';
import { App } from '@/models';

const router = useRouter();
const route = useRoute();
const store = useStore();

const app = computed(() => store.getCurrent<App>());
const isFormNew = computed(() => {
  return route.name === 'app.new' || route.name === 'app.continue';
});
</script>
