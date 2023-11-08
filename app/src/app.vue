<template>
  <div :class="[store.user ? 'router-view' : '']">
    <LeftBar />
    <Suspense>
      <RouterView id="view" v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component :is="Component" />
        </transition>
        <Spinner v-if="store.loading" />
      </RouterView>
      <template #fallback>
        <Spinner />
      </template>
    </Suspense>
  </div>
</template>

<script setup lang="ts">
import LeftBar from './components/LeftBar.vue';
import Spinner from './components/Spinner.vue';
import { useStore } from './store/store';
const store = useStore();
</script>

<style scoped></style>
