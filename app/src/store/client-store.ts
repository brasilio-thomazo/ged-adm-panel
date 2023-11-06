import { defineStore } from 'pinia'
import { ref } from 'vue'
import lodash from 'lodash'

export const useClientStore = defineStore('client.store', () => {
  const rows = ref<Client[]>([])

  function add(payload: Client) {
    rows.value.unshift(payload)
  }

  function update(payload: Client) {
    rows.value = rows.value.map((r) => (r.id === payload.id ? payload : r))
  }

  function destroy(payload: Client) {
    const index = lodash.findIndex(rows.value, { id: payload.id })
    if (index >= 0) rows.value.splice(index, 1)
  }

  function setRows(payload: Client[]) {
    rows.value = payload
  }

  function getRow(id: string) : Client | undefined {
    return lodash.find(rows.value, { id })
  }

  return { rows, update, destroy, setRows, getRow, add }
})
