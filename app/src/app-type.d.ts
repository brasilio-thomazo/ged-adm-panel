import { type } from 'os';

declare global {
  type IDepartment = {
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
  };

  type ISearch = {
    id: number;
    name: string;
    show_field: {
      department: boolean;
      document_type: boolean;
      name: boolean;
      identity: boolean;
      comment: boolean;
      storage: boolean;
      code: boolean;
    };
    created_at: string;
    updated_at: string;
  };

  type IRule = {
    documents: 'name' | 'identity' | 'department_id';
    users: 'name' | 'identity' | 'department_id';
  };

  type IGroup = {
    id: number;
    name: string;
    privileges: {
      group?: 'r' | 'rw' | undefined;
      user?: 'r' | 'rw' | undefined;
      document?: 'r' | 'rw' | undefined;
      search?: 'r' | 'rw' | undefined;
      authorities: string[];
    };
    types: number[];
    departments: number[];
    searches: number[];
    custom: IRule[];
    created_at: string;
    updated_at: string;
  };

  type IUser = {
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
    groups: IGroup[];
  };

  type IDocumentType = {
    id: number;
    name: string;
    created_at: string;
    updated_at: string;
  };

  type IGroupRequest = {
    name: string;
    privileges: {
      group?: 'r' | 'rw' | undefined;
      user?: 'r' | 'rw' | undefined;
      document?: 'r' | 'rw' | undefined;
      search?: 'r' | 'rw' | undefined;
    };
    types: number[];
    departments: number[];
    searches: number[];
    custom: IRule[];
  };

  type IUserRequest = {
    name: string;
    identity: string;
    department_id: number;
    phone: string;
    email: string;
    username: string;
    password?: string;
    password_confirmation?: string;
    groups: number[];
  };

  type IDocumentTypeRequest = {
    name: string;
  };

  type IDepartmentRequest = {
    name: string;
  };

  type ISearchRequest = {
    name: string;
    show_field: {
      department: boolean;
      document_type: boolean;
      name: boolean;
      identity: boolean;
      comment: boolean;
      storage: boolean;
      code: boolean;
    };
  };

  type IGroupReply = {
    message?: string;
    errors?: {
      name?: string[];
      privileges?: string[];
      types?: string[];
      departments?: string[];
      searches?: string[];
      custom?: string[];
    };
  };

  type IUserReply = {
    message?: string;
    errors?: {
      name?: string[];
      identity?: string[];
      department_id?: string[];
      phone?: string[];
      email?: string[];
      username?: string[];
      password?: string[];
      password_confirmation?: string[];
      groups?: string[];
    };
  };

  type IDocumentTypeReply = {
    message?: string;
    errors?: {
      name?: string[];
    };
  };

  type IDepartmentReply = {
    message?: string;
    errors?: {
      name?: string[];
    };
  };

  type ISearchReply = {
    message?: string;
    errors?: {
      name?: string[];
      show_field?: string[];
    };
  };

  type App = {
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
    created_at: number;
    updated_at: number;
    url_kubernetes: string;
    url_subscribe: string;
    client: Client;
    database_config: DatabaseConfig | undefined;
    cache_config: CacheConfig | undefined;
  };

  type AppRequest = {
    application: 0 | 1;
    client_id: string;
    path: string;
    use_domain: boolean;
    domain?: string;
    use_s3: boolean;
    aws_access_key_id?: string;
    aws_secret_access_key?: string;
    aws_region?: string;
    aws_bucket?: string;
  };

  type AppReply = {
    message?: string;
    errors?: {
      application?: string[];
      client_id?: string[];
      path?: string[];
      domain?: string[];
      aws_access_key_id?: string[];
      aws_secret_access_key?: string[];
      aws_region?: string[];
      aws_bucket?: string[];
    };
  };

  type DatabaseConfig = {
    id: number;
    app_id: string;
    custom: boolean;
    driver: 'mysql' | 'pgsql' | 'sqlite' | 'sqlsrv' | 'mongodb';
    writer_host: string;
    writer_port: number;
    reader_host: string;
    reader_port: number;
    database: string;
    created_at: number;
    updated_at: number;
  };

  type DatabaseConfigRequest = {
    app_id: string;
    custom: boolean;
    driver?: 'mysql' | 'pgsql' | 'sqlite' | 'sqlsrv' | 'mongodb' | undefined;
    writer_host?: string;
    writer_port?: number;
    reader_host?: string;
    reader_port?: number;
    database?: string;
    username?: string;
    password?: string;
    super_username?: string;
    super_password?: string;
  };

  type DatabaseConfigReply = {
    message?: string;
    errors?: {
      driver?: string[];
      writer_host?: string[];
      writer_port?: string[];
      reader_host?: string[];
      reader_port?: string[];
      database?: string[];
      username?: string[];
      password?: string[];
      super_username?: string[];
      super_password?: string[];
    };
  };

  type CacheConfig = {
    id: number;
    app_id: string;
    custom: boolean;
    driver: 'redis' | 'memcached' | 'file';
    host: string;
    port: number;
    created_at: number;
    updated_at: number;
  };

  type CacheConfigRequest = {
    app_id: string;
    custom: boolean;
    driver?: 'redis' | 'memcached' | 'file' | undefined;
    host?: string;
    port?: number;
    username?: string;
    password?: string;
  };

  type CacheConfigReply = {
    message?: string;
    errors?: {
      driver?: string[];
      host?: string[];
      port?: string[];
      username?: string[];
      password?: string[];
    };
  };
}
