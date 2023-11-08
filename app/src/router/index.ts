import { useStore } from '@/store/store';
import {
  NavigationGuardNext,
  RouteLocationNormalized,
  RouteRecordRaw,
  createRouter,
  createWebHashHistory,
} from 'vue-router';

const routes: RouteRecordRaw[] = [
  {
    name: 'login',
    path: '/login',
    component: () => import('@/views/login.vue'),
    meta: { require_auth: false },
  },
  {
    name: 'not_found',
    path: '/:pathMatch(.*)*',
    component: () => import('@/views/404.vue'),
    meta: { require_auth: false },
  },
  {
    name: 'home',
    path: '/',
    component: () => import('@/views/home.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'user',
    path: '/user',
    component: () => import('@/views/user.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'user.new',
    path: '/user/new',
    component: () => import('@/views/user.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'user.edit',
    path: '/user/:id/edit',
    component: () => import('@/views/user.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'group',
    path: '/group',
    component: () => import('@/views/group.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'group.new',
    path: '/group/new',
    component: () => import('@/views/group.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'group.edit',
    path: '/group/:id/edit',
    component: () => import('@/views/group.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'client',
    path: '/client',
    component: () => import('@/views/client.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'client.new',
    path: '/client/new',
    component: () => import('@/views/client.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'client.edit',
    path: '/client/:id/edit',
    component: () => import('@/views/client.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'app',
    path: '/app',
    component: () => import('@/views/app.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'app.new',
    path: '/app/new',
    component: () => import('@/views/app.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'app.continue',
    path: '/app/:id/continue',
    component: () => import('@/views/app.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'app.edit',
    path: '/app/:id/edit',
    component: () => import('@/views/app.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'app.show',
    path: '/app/:id',
    component: () => import('@/views/app.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'app.group',
    path: '/app/:app/group',
    component: () => import('@/views/app/group.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'app.group.new',
    path: '/app/:app/group/new',
    component: () => import('@/views/app/group.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'app.group.edit',
    path: '/app/:app/group/:id/edit',
    component: () => import('@/views/app/group.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'app.user',
    path: '/app/:app/user',
    component: () => import('@/views/app/user.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'app.user.new',
    path: '/app/:app/user/new',
    component: () => import('@/views/app/user.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'app.user.edit',
    path: '/app/:app/user/:id/edit',
    component: () => import('@/views/app/user.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'app.document-type',
    path: '/app/:app/document-type',
    component: () => import('@/views/app/document-type.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'app.document-type.new',
    path: '/app/:app/document-type/new',
    component: () => import('@/views/app/document-type.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'app.document-type.edit',
    path: '/app/:app/document-type/:id/edit',
    component: () => import('@/views/app/document-type.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'app.department',
    path: '/app/:app/department',
    component: () => import('@/views/app/department.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'app.department.new',
    path: '/app/:app/department/new',
    component: () => import('@/views/app/department.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'app.department.edit',
    path: '/app/:app/department/:id/edit',
    component: () => import('@/views/app/department.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'app.search',
    path: '/app/:app/search',
    component: () => import('@/views/app/search.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'app.search.new',
    path: '/app/:app/search/new',
    component: () => import('@/views/app/search.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'app.search.edit',
    path: '/app/:app/search/:id/edit',
    component: () => import('@/views/app/search.vue'),
    meta: { require_auth: true },
  },
  {
    name: 'profile',
    path: '/profile',
    component: () => import('@/views/profile.vue'),
    meta: { require_auth: true },
  },
];

const router = createRouter({
  routes,
  history: createWebHashHistory(),
});

router.beforeEach(async (to, _from, next) => {
  await middleware(to, next);
});

router.afterEach(() => {
  const store = useStore();
  store.setLoading(false);
});

const middleware = async (
  to: RouteLocationNormalized,
  next: NavigationGuardNext
) => {
  const store = useStore();
  store.setLoading(true);
  if (!to.meta.require_auth) return next();

  const name = to.name?.toString() || '';
  try {
    if (!store.user) await store.loadProfile();
  } catch (err: any) {
    if (err.response?.status === 401) {
      next({ name: 'login' });
    } else {
      console.log('ERROR', err);
    }
  }

  if (/^(group|user|client|app)(\.(new|continue|edit)|)/.test(name)) {
    const rName = name.split('.')[0] || name;
    const result = await store.getAll(rName as IRoute);
    if (result.status === 401) {
      alert('Você não tem permissão para acessar esta página');
      return next({ name: 'home' });
    } else if (result.status === 404) {
      if (result.response?.data?.message) alert(result.response?.data?.message);
      return next({ name: 'not_found' });
    }
  }

  if (/^user\.(edit|new)$/.test(name)) {
    const result = await store.getAll('group');
    if (result.status === 401) {
      alert('Você não tem permissão para acessar esta página');
      return next({ name: 'home' });
    } else if (result.status === 404) {
      if (result.response?.data?.message) alert(result.response?.data?.message);
      return next({ name: 'not_found' });
    }
  }

  if (/·*\.new$/.test(name)) {
    console.log('CLEAR CURRENT');
    store.clearCurrent();
  }

  if (/^app\.(edit|new)$/.test(name)) {
    const result = await store.getAll('client');
    if (result.status === 401) {
      alert('Você não tem permissão para acessar esta página');
      return next({ name: 'home' });
    } else if (result.status === 404) {
      if (result.response?.data?.message) alert(result.response?.data?.message);
      return next({ name: 'not_found' });
    }
  }

  if (/^app\.show$/.test(name)) {
    const result = await store.get(to.params.id, 'app');
    if (result.status === 200) {
      const app = store.getCurrent<IApp>();
      if (!app.cache_config || !app.database_config)
        return next({ name: 'app.continue', params: { id: app.id } });
      return next();
    } else if (result.status === 401) {
      alert('Você não tem permissão para acessar esta página');
      return next({ name: 'home' });
    } else if (result.status === 404) {
      if (result.response?.data?.message) alert(result.response?.data?.message);
      return next({ name: 'not_found' });
    }
  }

  if (/^(group|user|client|app)\.(show|edit|continue)$/.test(name)) {
    const rName = name.split('.')[0];
    const result = await store.get(to.params.id, rName as IRoute);
    if (result.status === 200) return next();
    else if (result.status === 401) {
      alert('Você não tem permissão para acessar esta página');
      return next({ name: 'home' });
    } else if (result.status === 404) {
      if (result.response?.data?.message) alert(result.response?.data?.message);
      return next({ name: 'not_found' });
    }
  }

  return next();
};

export default router;
