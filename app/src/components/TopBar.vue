<template>
  <div id="topbar">
    <ul>
      <li v-for="nav in navs" :key="nav.name">
        <RouterLink :to="nav.to">
          <span class="text">{{ nav.text }}</span>
        </RouterLink>
      </li>
    </ul>
  </div>
</template>
<script lang="ts" setup>
import { ref, watchEffect } from 'vue';
import { useRoute } from 'vue-router';
const props = defineProps<{
  path: 'user' | 'group' | 'client' | 'profile' | 'app';
}>();
const route = useRoute();

type INav = {
  to: string;
  text: string;
  name: string;
};
const navs = ref<INav[]>([]);
const trRoot = {
  user: 'usuários',
  group: 'grupos',
  client: 'organizações',
  profile: 'minha conta',
  app: 'aplicações',
};

watchEffect(() => {
  navs.value = [];
  const reEdit = new RegExp(`^${props.path}\.edit$`);
  const reNew = new RegExp(`^${props.path}\.new$`);
  const reShow = new RegExp(`^${props.path}\.show$`);
  const reContinue = new RegExp(`^${props.path}\.continue$`);
  const name = route.name?.toString() || '';
  navs.value.push({
    to: `/${props.path}`,
    text: trRoot[props.path],
    name: props.path,
  });
  if (reEdit.test(name)) {
    navs.value.push({
      to: `/${props.path}/${route.params.id}/edit`,
      text: 'editando',
      name: `${props.path}.edit`,
    });
  } else if (reNew.test(name)) {
    navs.value.push({
      to: `/${props.path}/new`,
      text: 'novo',
      name: `${props.path}.new`,
    });
  }
  if (reShow.test(name)) {
    navs.value.push({
      to: `/${props.path}/${route.params.id}`,
      text: 'visualizando',
      name: `${props.path}.show`,
    });
  }
  if (reContinue.test(name)) {
    navs.value.push({
      to: `/${props.path}/${route.params.id}/continue`,
      text: 'continuando',
      name: `${props.path}.continue`,
    });
  }
});
</script>
