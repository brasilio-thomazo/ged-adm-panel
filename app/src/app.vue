<template>
  <div :class="[store.user ? 'router-view' : '']">
    <LeftBar />
    <Suspense>
      <RouterView id="view" />
    </Suspense>
  </div>
</template>

<script setup lang="ts">
import LeftBar from './components/LeftBar.vue';
import { useStore } from './store/store';
const store = useStore();
</script>

<style scoped></style>
