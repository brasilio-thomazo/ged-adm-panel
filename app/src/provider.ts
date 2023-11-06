import { MaskaDetail } from 'maska';

export function phoneMask(str: string) {
  const regex = /([0-9]{2})(9?)([0-9]{4})([0-9]{4})/;
  return str.replace(regex, '($1) $2$3-$4');
}

export function dateFormat(str: string) {
  return new Date(str).toLocaleString('pt-BR').replace(',', '\n');
}

export function parseDate(str: string) {
  return str.replace(/([0-9]{4})-([0-9]{2})-([0-9]{2})/, '$3/$2/$1');
}

export function maskDocument(str: string) {
  const cpf = /([0-9]{3})([0-9]{3})([0-9]{3})([0-9]{2})/;
  const cnpj = /([0-9]{2})([0-9]{3})([0-9]{3})([0-9]{4})([0-9]{2})/;
  if (cnpj.test(str)) return str.replace(cnpj, '$1.$2.$3/$4-$5');
  return str.replace(cpf, '$1.$2.$3-$4');
}

export function mask(str: string, isPhone = false): string {
  const cpf = /([0-9]{3})([0-9]{3})([0-9]{3})([0-9]{2})/;
  const cnpj = /([0-9]{2})([0-9]{3})([0-9]{3})([0-9]{4})([0-9]{2})/;
  const phone = /([0-9]{2})(9?)([0-9]{4})([0-9]{4})/;
  if (cnpj.test(str)) return str.replace(cnpj, '$1.$2.$3/$4-$5');
  else if (cpf.test(str) && !isPhone) return str.replace(cpf, '$1.$2.$3-$4');
  return str.replace(phone, '($1) $2$3-$4');
}

export function interval(str: string | undefined) {
  if (!str) return '0 00:00:00';
  const start = new Date(str);
  const now = new Date();
  let ms = now.getTime() - start.getTime();
  const s = Math.floor((ms / 1000) % 60);
  const m = Math.floor((ms / 1000 / 60) % 60);
  const h = Math.floor((ms / 1000 / 60 / 60) % 60);
  const d = Math.floor((ms / 1000 / 60 / 60 / 24) % 60);

  const seconds = `${s}`.padStart(2, '0');
  const minutes = `${m}`.padStart(2, '0');
  const hours = `${h}`.padStart(2, '0');
  const days = `${d}`.padStart(2, '0');

  return `${days} ${hours}:${minutes}:${seconds}`;
}

export function intervalFromTime(time: number | undefined) {
  if (!time) return '0 00:00:00';
  const now = Math.floor(new Date().getTime() / 1000);
  console.log(time, now, now - time);

  let ms = now - time;
  const s = Math.floor(ms % 60);
  const m = Math.floor((ms / 60) % 60);
  const h = Math.floor((ms / 60 / 60) % 60);
  const d = Math.floor((ms / 60 / 60 / 24) % 60);

  const seconds = `${s}`.padStart(2, '0');
  const minutes = `${m}`.padStart(2, '0');
  const hours = `${h}`.padStart(2, '0');
  const days = `${d}`.padStart(2, '0');

  return `${days} ${hours}:${minutes}:${seconds}`;
}

export const db_drivers = [
  { value: 'mysql', text: 'MySQL' },
  { value: 'pgsql', text: 'PostgreSQL' },
  { value: 'sqlite', text: 'SQLite' },
  { value: 'sqlsrv', text: 'SQL Server' },
  { value: 'mongodb', text: 'MongoDB' },
];

export const cache_drivers = [
  { value: 'file', text: 'File' },
  { value: 'redis', text: 'Redis' },
];

export const maskDetail: MaskaDetail = {
  masked: '',
  unmasked: '',
  completed: false,
};

export const groupRequest: GroupRequest = {
  name: '',
  privileges: {},
};

export const userRequest: UserRequest = {
  name: '',
  email: '',
  username: '',
  password: '',
  password_confirmation: '',
  phone: '',
  identity: '',
  role: '',
  groups: [],
};

export const clientRequest: ClientRequest = {
  name: '',
  email: '',
  phone: '',
  identity: '',
  scope: 'client',
  manager: '',
  role: '',
};

export const appRequest: AppRequest = {
  application: 0,
  path: '',
  client_id: '',
  use_domain: false,
  use_s3: false,
};

export const configDatabaseRequest: DatabaseConfigRequest = {
  app_id: '',
  custom: false,
  database: '',
};

export const configCacheRequest: CacheConfigRequest = {
  app_id: '',
  custom: false,
};

export const appGroupRequest: IGroupRequest = {
  name: '',
  types: [],
  departments: [],
  searches: [],
  custom: [],
  privileges: {},
};

export const appUserRequest: IUserRequest = {
  name: '',
  email: '',
  phone: '',
  identity: '',
  username: '',
  department_id: 3,
  groups: [],
};
