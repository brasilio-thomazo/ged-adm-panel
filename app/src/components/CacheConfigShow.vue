<template>
  <div v-if="config" class="show">
    <fieldset>
      <legend>Cache</legend>
      <div class="content">
        <div class="line">
          <div class="label">Driver:</div>
          <div class="data">{{ config.driver }}</div>
        </div>
        <div class="line">
          <div class="label">Servidor:</div>
          <div class="data">{{ config.host }}</div>
        </div>
        <div class="line">
          <div class="label">Porta:</div>
          <div class="data">{{ config.port }}</div>
        </div>
      </div>
    </fieldset>
  </div>
</template>
<script lang="ts" setup>
import { useStore } from '@/store/store';

const store = useStore();
const config = store.getCurrent<IApp>().cache_config;
</script>
