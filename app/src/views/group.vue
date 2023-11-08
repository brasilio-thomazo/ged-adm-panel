<template>
  <div>
    <TopBar path="group" />
    <div class="view-content">
      <GroupForm v-if="isForm" />
      <GroupList v-else>
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
import { useRoute, useRouter } from 'vue-router';
import TopBar from '@/components/TopBar.vue';
import { computed } from 'vue';
import _ from 'lodash';

const route = useRoute();
const router = useRouter();
const isForm = computed(() => {
  const name = route.name?.toString() || '';
  return /.*\.(new|edit)$/.test(name);
});
</script>
