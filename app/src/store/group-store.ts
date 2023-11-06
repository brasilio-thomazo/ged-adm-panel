import { defineStore } from 'pinia'
import { ref } from 'vue'
import lodash from 'lodash'

export const useGroupStore = defineStore('group.store', () => {
  const rows = ref<Group[]>([])

  function add(payload: Group) {
    rows.value.unshift(payload)
  }

  function update(payload: Group) {
    rows.value = rows.value.map((r) => (r.id === payload.id ? payload : r))
  }

  function remove(payload: Group) {
    const index = lodash.findIndex(rows.value, { id: payload.id })
    if (index >= 0) rows.value.splice(index, 1)
  }

  function setRows(payload: Group[]) {
    rows.value = payload
  }

  function getRow(id: string) {
    return lodash.find(rows.value, { id })
  }

  return { rows, add, update, remove, setRows, getRow }
})
