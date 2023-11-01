<template>
  <div>
    <div class="group-view">
      <GroupList @edit="edit" />
      <hr />
      <GroupForm :data="current" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { useGroupStore } from "@/store/group-store";
import GroupList from "@components/GroupList.vue";
import { inject, ref } from "vue";
import axios from "axios";
import _ from "lodash";
import GroupForm from "@/components/GroupForm.vue";

const store = useGroupStore();
const http = inject("http", axios);
const current = ref<Group | null>(null);
const { data: groups } = await http.get<Group[]>("groups");
store.setRows(groups);

function edit(data: Group) {
  console.log(data);
  current.value = _.cloneDeep(data);
}

// try {
//   if (store.rows.length === 0) {
//     const { data } = await http.get<Group[]>("group");
//     store.setRows(data);
//   }
//   if (dtype.rows.length === 0) {
//     const { data } = await http.get<DocumentType[]>("document_type");
//     dtype.setRows(data);
//   }
//   if (search.rows.length === 0) {
//     const { data } = await http.get<Search[]>("search");
//     search.setRows(data);
//   }
//   if (department.rows.length === 0) {
//     const { data } = await http.get<Department[]>("department");
//     department.setRows(data);
//   }
// } catch ({ response }: any) {
//   console.log("ERROR: ", response);
// }
</script>
