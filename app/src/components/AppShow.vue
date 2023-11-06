<template>
  <div class="show">
    <fieldset>
      <legend>Aplicação</legend>
      <div class="content">
        <div class="line">
          <div class="label">Organização:</div>
          <div class="data">{{ app.client.name }}</div>
        </div>
        <div class="line">
          <div class="label">Path:</div>
          <div class="data">{{ app.path }}</div>
        </div>
        <div class="line">
          <div class="label">Domínio:</div>
          <div class="data">{{ app.domain ? app.domain : '-' }}</div>
        </div>
      </div>
    </fieldset>
    <fieldset>
      <legend>AWS S3</legend>
      <div class="content">
        <div class="line">
          <div class="label">Região:</div>
          <div class="data">{{ app.aws_region ? app.aws_region : '-' }}</div>
        </div>
        <div class="line">
          <div class="label">Bucket:</div>
          <div class="data">{{ app.aws_bucket ? app.aws_bucket : '-' }}</div>
        </div>
      </div>
    </fieldset>
  </div>
</template>
<script lang="ts" setup>
import { computed } from 'vue';
const props = defineProps<{ app: App }>();
const app = computed(() => props.app);
</script>
