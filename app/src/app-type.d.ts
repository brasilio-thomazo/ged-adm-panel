import { type } from "os";

declare global {

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
  }

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
  }

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
  }

  type DatabaseConfig = {
    id: number;
    app_id: string;
    custom: boolean;
    driver: "mysql" | "pgsql" | "sqlite" | "sqlsrv" | "mongodb";
    writer_host: string;
    writer_port: number;
    reader_host: string;
    reader_port: number;
    database: string;
    created_at: number;
    updated_at: number;
  }

  type DatabaseConfigRequest = {
    app_id: string;
    custom: boolean;
    driver?: "mysql" | "pgsql" | "sqlite" | "sqlsrv" | "mongodb" | undefined;
    writer_host?: string;
    writer_port?: number;
    reader_host?: string;
    reader_port?: number;
    database?: string;
    username?: string;
    password?: string;
    super_username?: string;
    super_password?: string;
  }

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
  }

  type CacheConfig = {
    id: number;
    app_id: string;
    custom: boolean;
    driver: "redis" | "memcached" | "file";
    host: string;
    port: number;
    created_at: number;
    updated_at: number;
  }

  type CacheConfigRequest = {
    app_id: string;
    custom: boolean;
    driver?: "redis" | "memcached" | "file" | undefined;
    host?: string;
    port?: number;
    username?: string;
    password?: string;
  }

  type CacheConfigReply = {
    message?: string;
    errors?: {
      driver?: string[];
      host?: string[];
      port?: string[];
      username?: string[];
      password?: string[];
    };
  }

  interface AppPrivilege {
    group?: Permission;
    user?: Permission;
    document?: Permission;
    search?: Permission;
    authorities: string[];
  }

  interface AppPrivilegeRequest {
    group?: Permission;
    user?: Permission;
    document?: Permission;
    search?: Permission;
  }

  interface AppDocumentType {
    id: number;
    name: string;
    created_at: number;
    updated_at: number;
  }

  interface AppDocumentTypeRequest {
    name: string;
  }

  interface AppDepartment {
    id: number;
    name: string;
    created_at: number;
    updated_at: number;
  }

  interface AppGroup {
    id: number;
    name: string;
    privilege: AppPrivilege;
    authorities: string[];
    created_at: number;
    updated_at: number;
  }

  interface AppUser {
    id: string;
    name: string;
    identity: string;
    department_id: number;
    phone: string;
    email: string;
    username: string;
    created_at: number;
    updated_at: number;
    department: AppDepartment;
    groups: AppGroup[];
  }

  interface AppUserRequest {
    name: string;
    identity: string;
    department_id: number;
    phone: string;
    email: string;
    username: string;
    password: string;
    password_confirmation: string;
    groups: number[];
  }

  interface AppSearch {
    id: number;
    name: string;
    show_field: {};
    created_at: number;
    updated_at: number;
  }
}