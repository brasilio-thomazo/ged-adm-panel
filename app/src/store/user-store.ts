import { defineStore } from 'pinia'
import { ref } from 'vue'
import lodash from 'lodash'

export const useUserStore = defineStore('user.store', () => {
  const rows = ref<User[]>([])

  function add(payload: User) {
    rows.value.unshift(payload)
  }

  function update(payload: User) {
    rows.value = rows.value.map((r) => (r.id === payload.id ? payload : r))
  }

  function remove(payload: User) {
    const index = lodash.findIndex(rows.value, { id: payload.id })
    if (index >= 0) rows.value.splice(index, 1)
  }

  function setRows(payload: User[]) {
    rows.value = payload
  }

  function getRow(id: string) {
    return lodash.find(rows.value, { id })
  }

  return { rows, add, update, remove, setRows, getRow }
})
