declare global {
  type IPermission = 'r' | 'rw' | undefined;
  type ICacheDriver = 'redis' | 'file';
  type IDatabaseDriver = 'mysql' | 'pgsql' | 'sqlite' | 'sqlsrv' | 'mongodb';

  type IRule = {
    documents: 'name' | 'identity' | 'department_id';
    users: 'name' | 'identity' | 'department_id';
  };

  type INameOnly = {
    id: number;
    name: string;
    created_at: string;
    updated_at: string;
  };

  type ISearchField = {
    department: boolean;
    document_type: boolean;
    name: boolean;
    identity: boolean;
    comment: boolean;
    storage: boolean;
    code: boolean;
  };

  interface IPrivilege {
    group?: IPermission;
    user?: IPermission;
    client?: IPermission;
    app?: IPermission;
    authorities: string[];
  }

  interface IUser {
    id: string;
    name: string;
    identity: string;
    role: string;
    phone: string;
    email: string;
    username: string;
    groups: IGroup[];
    created_at: string;
    updated_at: string;
  }

  interface IGroup {
    id: number;
    name: string;
    is_admin: boolean;
    privileges: IPrivilege;
    created_at: string;
    updated_at: string;
  }

  interface IClient {
    id: string;
    name: string;
    identity: string;
    email: string;
    phone: string;
    scope: 'client' | 'provider';
    manager: string;
    role: string;
    created_at: string;
    updated_at: string;
  }

  interface IDatabaseConfig {
    id: number;
    app_id: string;
    custom: boolean;
    driver: IDatabaseDriver;
    writer_host: string;
    writer_port: number;
    reader_host: string;
    reader_port: number;
    database: string;
    created_at: number;
    updated_at: number;
  }

  interface ICacheConfig {
    id: number;
    app_id: string;
    custom: boolean;
    driver: ICacheDriver;
    host: string;
    port: number;
    created_at: number;
    updated_at: number;
  }

  interface IApp {
    id: string;
    client_id: string;
    application: 0 | 1;
    path: string;
    subdomain: string;
    use_domain: boolean;
    domain?: string;
    use_s3: boolean;
    aws_region?: string;
    aws_bucket?: string;
    grpc_port: number;
    installed_at?: number;
    started_at?: number;
    created_at: string;
    updated_at: string;
    url_kubernetes: string;
    url_subscribe: string;
    client: IClient;
    database_config: IDatabaseConfig | undefined;
    cache_config: ICacheConfig | undefined;
  }

  interface IDepartment {
    id: number;
    name: string;
    created_at: string;
    updated_at: string;
  }

  interface IAppPrivilege {
    group?: IPermission;
    user?: IPermission;
    document?: IPermission;
    search?: IPermission;
    authorities: string[];
  }

  interface IAppGroup {
    id: number;
    name: string;
    privileges: IAppPrivilege;
    types: number[];
    departments: number[];
    searches: number[];
    custom: IRule[];
    created_at: string;
    updated_at: string;
  }

  interface IAppUser {
    id: string;
    name: string;
    identity: string;
    department_id: number;
    phone: string;
    email: string;
    username: string;
    created_at: string;
    updated_at: string;
    department: IDepartment;
    groups: IAppGroup[];
  }

  interface IDocumentType extends INameOnly {}

  interface ISearch extends INameOnly {
    show_field: ISearchField;
  }
}

export {};
