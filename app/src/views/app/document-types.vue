<template>
  <div>
    <div class="views">
      <AppDocumentTypeList @edit="edit" />
      <hr />
      <AppDocumentTypeForm :data="current" :id="id" />
    </div>
  </div>
</template>

<script setup lang="ts">
import AppDocumentTypeList from "@/components/AppDocumentTypeList.vue";
import AppDocumentTypeForm from "@/components/AppDocumentTypeForm.vue";
import { useAppStore } from "@/store/app-store";
import { useRoute } from "vue-router";
import { inject, ref } from "vue";
import axios from "axios";

const http = inject("http", axios);
const appStore = useAppStore();
const current = ref<AppDocumentType | null>(null);
const route = useRoute();

const id = route.params.id;
const { data: documentTypes } = await http.get<AppDocumentType[]>(
  `app/${id}/document_types`
);
appStore.setDocumentTypes(documentTypes);

function edit(data: AppDocumentType) {
  current.value = data;
}
</script>
