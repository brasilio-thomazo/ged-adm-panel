<template>
  <fieldset v-if="config">
    <legend>Banco de dados</legend>
    <div class="content">
      <div class="line">
        <div class="label">Driver:</div>
        <div class="data">{{ config.driver }}</div>
      </div>
      <div class="line">
        <div class="label">Servidor master:</div>
        <div class="data">{{ config.writer_host }}</div>
      </div>
      <div class="line">
        <div class="label">Porta master:</div>
        <div class="data">{{ config.writer_port }}</div>
      </div>
      <div class="line">
        <div class="label">Servidor slave:</div>
        <div class="data">{{ config.reader_host }}</div>
      </div>
      <div class="line">
        <div class="label">Porta slave:</div>
        <div class="data">{{ config.reader_port }}</div>
      </div>
      <div class="line">
        <div class="label">Database:</div>
        <div class="data">{{ config.database }}</div>
      </div>
    </div>
  </fieldset>
</template>
<script lang="ts" setup>
import { computed } from 'vue';
const props = defineProps<{ app: App }>();
const config = computed(() => props.app.database_config);
</script>
