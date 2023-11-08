import axios from 'axios';
import { defineStore } from 'pinia';
import { Ref, ref } from 'vue';
import _ from 'lodash';

import { Group, User, Client, App, ORMUtil } from '@/models';

export const useStore = defineStore('main.store', () => {
  const user = ref<IUser>();
  const is_admin = ref<boolean>(false);
  const loading = ref<boolean>(true);

  const current = ref<any>();

  const cls = new ORMUtil();

  const groups = ref<Group[]>([]);
  const users = ref<User[]>([]);
  const clients = ref<Client[]>([]);
  const apps = ref<App[]>([]);
  const appGroups = ref<Group[]>([]);
  const appUsers = ref<User[]>([]);
  const appDepartments = ref<IDepartment[]>([]);
  const appDocumentTypes = ref<IDocumentType[]>([]);

  const loadProfile = async () => {
    const response = await http().get('me');
    setUser(response.data);
  };

  const getList = (route: IRoute): Ref<any[]> => {
    switch (route) {
      case 'group':
        return groups;
      case 'user':
        return users;
      case 'client':
        return clients;
      case 'app':
      case 'database_config':
      case 'cache_config':
        return apps;
      case 'app.document-type':
        return appDocumentTypes;
      case 'app.department':
        return appDepartments;
      case 'app.search':
        return apps;
    }
  };

  // function refList<T>(type: { new (): T }): Ref<T[]> {
  //   if (type === Group) return groups as Ref<T[]>;
  //   if (type === User) return users as Ref<T[]>;
  //   if (type === Client) return clients as Ref<T[]>;
  //   if (type === App) return apps as Ref<T[]>;
  //   return ref([]);
  // }

  function list<T>(type: { new (): T }): T[] {
    if (type === Group) return groups.value as T[];
    if (type === User) return users.value as T[];
    if (type === Client) return clients.value as T[];
    if (type === App) return apps.value as T[];
    return [];
  }

  function clearCurrent() {
    console.log('clearCurrent');
    current.value = undefined;
  }

  const getCurrent = <T>() => {
    console.log('getCurrent', current.value);
    return current.value as T;
  };

  const setCurrent = (payload: any) => {
    console.log('setCurrent', payload);
    current.value = payload;
  };

  const getAll = async (name: IRoute) => {
    return await cls.all(getList(name), name);
  };

  const get = async (id: any, name: IRoute) => {
    return cls.get(current, getList(name), id, name);
  };

  const create = async (request: any, name: IRoute) => {
    return await cls.create(getList(name), name, request);
  };

  const update = async (id: any, request: any, name: IRoute) => {
    return await cls.update(getList(name), name, id, request);
  };

  const remove = async (id: any, name: IRoute) => {
    return await cls.remove(getList(name), name, id);
  };

  function setUser(payload: User) {
    user.value = payload;
    is_admin.value = _.findIndex(payload.groups, { is_admin: true }) >= 0;
  }

  function setToken(payload: string) {
    localStorage.setItem('token', payload);
  }

  function http() {
    const token = localStorage.getItem('token');
    axios.defaults.baseURL = import.meta.env.VITE_API_URL;
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    axios.defaults.headers.common['Content-Type'] = 'application/json';
    axios.defaults.headers.common.Accept = 'application/json';
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    return axios;
  }

  function setLoading(payload: boolean) {
    loading.value = payload;
  }

  return {
    clearCurrent,
    setCurrent,
    list,
    getAll,
    get,
    create,
    getCurrent,
    update,
    remove,
    loadProfile,
    groups,
    setUser,
    setToken,
    user,
    http,
    is_admin,
    loading,
    setLoading,
    users,
    clients,
    apps,
    appGroups,
    appUsers,
    appDepartments,
    appDocumentTypes,
  };
});
