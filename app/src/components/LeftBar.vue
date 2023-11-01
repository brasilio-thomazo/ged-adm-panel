<template>
  <nav v-if="store.user" class="left-bar">
    <div class="brand">
      <RouterLink to="/">ADM Panel</RouterLink>
    </div>
    <ul class="menu">
      <li>
        <RouterLink to="/groups">Grupos</RouterLink>
      </li>
      <li>
        <RouterLink to="/users">Usuários</RouterLink>
      </li>
      <li>
        <RouterLink to="/clients">Organizações</RouterLink>
      </li>
      <li>
        <details class="submenu">
          <summary>Aplicações</summary>
          <ul>
            <li>
              <RouterLink to="/apps">Registros</RouterLink>
            </li>
            <li v-for="app in apps">
              <details class="submenu">
                <summary>{{ app.path }}</summary>
                <ul>
                  <li>
                    <RouterLink :to="`/app/${app.id}/users`">
                      Usuários
                    </RouterLink>
                  </li>
                  <li>
                    <RouterLink :to="`/app/${app.id}/document_types`">
                      Tipos de documento
                    </RouterLink>
                  </li>
                  <li>
                    <RouterLink :to="`/app/${app.id}/searches`">
                      Pesquisas
                    </RouterLink>
                  </li>
                </ul>
              </details>
            </li>
          </ul>
        </details>
      </li>
    </ul>
  </nav>
</template>

<script setup lang="ts">
import { useStore } from "@/store/store";
import axios from "axios";
import { inject, onMounted, ref } from "vue";
// import { useRouter } from "vue-router";
const store = useStore();
const http = inject("http", axios);

const apps = ref<App[]>([]);

// const router = useRouter();

onMounted(async () => {
  try {
    const { data } = await http.get<App[]>("apps");
    apps.value = data;
  } catch ({ response }: any) {
    console.log(response);
  }
});
</script>
