import { defineStore } from 'pinia';
import { ref } from 'vue';
import lodash from 'lodash';

export const useAppStore = defineStore('app.store', () => {
  const rows = ref<App[]>([]);

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

  return {
    rows,
    add,
    update,
    remove,
    setRows,
    getRow,
    setConfigCache,
    setConfigDatabase,
  };
});
