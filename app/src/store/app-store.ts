import { defineStore } from "pinia";
import { ref } from "vue";
import lodash from "lodash";

export const useAppStore = defineStore("app.store", () => {
  const rows = ref<App[]>([]);
  const users = ref<AppUser[]>([]);
  const groups = ref<AppGroup[]>([]);
  const departments = ref<AppDepartment[]>([]);
  const documentTypes = ref<AppDocumentType[]>([]);

  function add(payload: App) {
    rows.value.unshift(payload);
  }

  function update(payload: App) {
    rows.value = rows.value.map((r) => (r.id === payload.id ? payload : r));
  }

  function remove(payload: App) {
    const index = lodash.findIndex(rows.value, { id: payload.id });
    if (index >= 0) rows.value.splice(index, 1);
  }

  function setConfigCache(payload: CacheConfig) {
    rows.value = rows.value.map((r) =>
      r.id === payload.app_id ? { ...r, config_cache: payload } : r
    );
  }

  function setConfigDatabase(payload: DatabaseConfig) {
    rows.value = rows.value.map((r) =>
      r.id === payload.app_id ? { ...r, config_database: payload } : r
    );
  }

  function getRow(id: string): App | undefined {
    return lodash.find(rows.value, { id });
  }

  function setRows(payload: App[]) {
    rows.value = payload;
  }

  function setDocumentTypes(payload: AppDocumentType[]) {
    documentTypes.value = payload;
  }

  function updateDocumentTypes(payload: AppDocumentType) {
    documentTypes.value = documentTypes.value.map((d) =>
      d.id === payload.id ? payload : d
    );
  }

  function addDocumentType(payload: AppDocumentType) {
    documentTypes.value.unshift(payload);
  }

  function removeDocumentType(payload: AppDocumentType) {
    const index = lodash.findIndex(documentTypes.value, { id: payload.id });
    if (index >= 0) documentTypes.value.splice(index, 1);
  }

  function setUsers(payload: AppUser[]) {
    users.value = payload;
  }

  function updateUsers(payload: AppUser) {
    users.value = users.value.map((u) => (u.id === payload.id ? payload : u));
  }

  function addUser(payload: AppUser) {
    users.value.unshift(payload);
  }

  function setGroups(payload: AppGroup[]) {
    groups.value = payload;
  }

  function setDepartments(payload: AppDepartment[]) {
    departments.value = payload;
  }

  return {
    rows,
    add,
    update,
    remove,
    setRows,
    getRow,
    setDocumentTypes,
    documentTypes,
    updateDocumentTypes,
    addDocumentType,
    removeDocumentType,
    setUsers,
    users,
    setGroups,
    updateUsers,
    addUser,
    groups,
    setDepartments,
    departments,
    setConfigCache,
    setConfigDatabase,
  };
});
