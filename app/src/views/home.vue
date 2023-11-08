<template>
  <div>Bem vindo de volta.</div>
</template>
<script setup lang="ts">
import { useStore } from '@/store/store';

const store = useStore();
store.setLoading(false);
</script>
