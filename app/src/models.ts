import axios from 'axios';
import { Ref } from 'vue';
import _ from 'lodash';

export class Privilege implements IPrivilege {
  group?: IPermission;
  user?: IPermission;
  client?: IPermission;
  app?: IPermission;
  authorities: string[];
  constructor() {
    this.authorities = [];
  }
}

export class Group implements IGroup {
  id = 0;
  name = '';
  is_admin = false;
  privileges = new Privilege();
  created_at = '';
  updated_at = '';
}

export class User implements IUser {
  id = '';
  name = '';
  identity = '';
  role = '';
  phone = '';
  email = '';
  username = '';
  groups: IGroup[] = [];
  created_at = '';
  updated_at = '';
}

export class Client implements IClient {
  id = '';
  name = '';
  identity = '';
  email = '';
  phone = '';
  scope: IAppScope = 'client';
  manager = '';
  role = '';
  created_at = '';
  updated_at = '';
}

export class App implements IApp {
  id = '';
  client_id = '';
  application = 0;
  path = '';
  subdomain = '';
  use_domain = false;
  domain = undefined;
  use_s3 = false;
  aws_region = undefined;
  aws_bucket = undefined;
  grpc_port = 0;
  installed_at = undefined;
  started_at = undefined;
  created_at = '';
  updated_at = '';
  url_kubernetes = '';
  url_subscribe = '';
  client = new Client();
  database_config: IDatabase | undefined = undefined;
  cache_config: ICache | undefined = undefined;
}

type ORM = { id: any };

export class ORMUtil {
  constructor() {}

  http = () => {
    const token = localStorage.getItem('token');
    axios.defaults.baseURL = import.meta.env.VITE_API_URL;
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    axios.defaults.headers.common['Content-Type'] = 'application/json';
    axios.defaults.headers.common.Accept = 'application/json';
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    return axios;
  };

  public all = async <T>(rows: Ref<T[]>, url: string): Promise<IResult> => {
    try {
      const response = await this.http().get<T[]>(url);
      rows.value = response.data;
      return { status: response.status, response };
    } catch (err: any) {
      const status = err.response?.status || 500;
      return { error: err, status, response: err.response };
    }
  };

  public get = async <T extends ORM>(
    row: Ref<T | undefined>,
    rows: Ref<T[]>,
    id: any,
    url: string
  ): Promise<IResult> => {
    const index = _.findIndex(rows.value, { id });
    try {
      const response = await this.http().get<T>(`${url}/${id}`);
      if (index >= 0) {
        rows.value[index] = response.data;
        row.value = rows.value[index];
      } else {
        rows.value.push(response.data);
        row.value = rows.value[rows.value.length - 1];
      }
      return { status: response.status, response };
    } catch (err: any) {
      const status = err.response?.status || 500;
      return { error: err, status, response: err.response };
    }
  };

  public create = async <T extends ORM>(
    list: Ref<T[]>,
    url: string,
    request: any
  ): Promise<IResult> => {
    try {
      const response = await this.http().post<T>(url, request);
      if (url == 'database_config' || url == 'cache_config') {
        const id = request.app_id;
        const index = _.findIndex(list.value, { id });
        if (index >= 0) {
          (list.value[index] as any)[url] = response.data;
        }
        console.log('CREATE', url, id, index, list.value);
      } else {
        list.value.push(response.data);
      }
      return { status: response.status, response };
    } catch (err: any) {
      const status = err.response?.status || 500;
      return { error: err, status, response: err.response };
    }
  };

  public update = async <T extends ORM>(
    list: Ref<T[]>,
    url: string,
    id: any,
    request: any
  ): Promise<IResult> => {
    try {
      const response = await this.http().put<T>(url, request);
      if (url == 'database_config' || url == 'cache_config') {
        const appId = request.app_id;
        const index = _.findIndex(list.value, { id: appId });
        if (index >= 0) {
          (list.value[index] as any)[url] = response.data;
        }
        console.log('UPDATE', url, appId, index);
        console.log('LIST:', list.value);
      } else {
        const index = _.findIndex(list.value, { id });
        if (index >= 0) {
          list.value[index] = response.data;
        }
      }
      return { status: response.status, response };
    } catch (err: any) {
      const status = err.response?.status || 500;
      return { error: err, status, response: err.response };
    }
  };

  public remove = async <T extends ORM>(
    rows: Ref<T[]>,
    url: string,
    id: any
  ): Promise<IResult> => {
    try {
      const response = await this.http().delete(`${url}/${id}`);
      const index = _.findIndex(rows.value, { id });
      if (index >= 0) {
        rows.value.splice(index, 1);
      }
      return { status: response.status, response };
    } catch (err: any) {
      const status = err.response?.status || 500;
      return { error: err, status, response: err.response };
    }
  };
}
