import { type } from 'os';

declare global {
  interface INameOnlyRequest {
    name: string;
  }

  interface IAuthRequest {
    username: string;
    password: string;
    device_name: string;
    remember?: boolean;
  }

  interface IProfileRequest {
    name: string;
    identity: string;
    role: string;
    phone: string;
    email: string;
  }

  interface IPasswordRequest {
    current_password: string;
    password: string;
    password_confirmation: string;
  }

  type IGroupRequest = {
    name: string;
    privileges: {
      group?: string;
      user?: string;
      client?: string;
      app?: string;
    };
  };

  type IUserRequest = {
    name: string;
    identity: string;
    role: string;
    phone: string;
    email: string;
    username: string;
    password: string;
    password_confirmation: string;
    groups: number[];
  };

  type IClientRequest = {
    name: string;
    identity: string;
    email: string;
    phone: string;
    scope: 'client' | 'provider';
    manager: string;
    role: string;
  };

  type IAppRequest = {
    client_id: string;
    application: number;
    path: string;
    use_domain: boolean;
    domain?: string;
    use_s3: boolean;
    aws_region?: string;
    aws_bucket?: string;
  };

  type IDatabaseRequest = {
    app_id: string;
    custom: boolean;
    driver?: IDatabaseDriver;
    writer_host?: string;
    writer_port?: number;
    reader_host?: string;
    reader_port?: number;
    database: string;
  };

  type ICacheRequest = {
    app_id: string;
    custom: boolean;
    driver?: ICacheDriver;
    host?: string;
    port?: number;
  };

  type IAppGroupRequest = {
    name: string;
    privileges: IAppPrivilege;
    types: number[];
    departments: number[];
    searches: number[];
    custom: IRule[];
  };

  type IAppUserRequest = {
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

  interface IDepartmentRequest extends INameOnlyRequest {}
  interface IDocumentTypeRequest extends INameOnlyRequest {}
  interface ISearchRequest extends INameOnlyRequest {
    show_field: ISearchField;
  }
}

export {};
