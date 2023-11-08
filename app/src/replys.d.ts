declare global {
  interface IReply {
    message?: string;
  }

  interface IAuthReply extends IReply {
    errors?: {
      username?: string[];
      password?: string[];
    };
  }

  interface IProfileReply extends IReply {
    errors?: {
      name?: string[];
      identity?: string[];
      role?: string[];
      phone?: string[];
      email?: string[];
    };
  }

  interface IPasswordReply extends IReply {
    errors?: {
      current_password?: string[];
      password?: string[];
      password_confirmation?: string[];
    };
  }

  interface IGroupReply extends IReply {
    errors?: {
      name?: string[];
      privileges?: string[];
    };
  }

  interface IUserReply extends IReply {
    errors?: {
      name?: string[];
      identity?: string[];
      role?: string[];
      phone?: string[];
      email?: string[];
      username?: string[];
      password?: string[];
      groups?: sring[];
    };
  }

  interface IClientReply extends IReply {
    errors?: {
      name?: string[];
      identity?: string[];
      email?: string[];
      phone?: string[];
      scope?: string[];
      manager?: string[];
      role?: string[];
    };
  }

  interface IAppReply extends IReply {
    errors?: {
      client_id?: string[];
      application?: string[];
      path?: string[];
      use_domain?: string[];
      domain?: string[];
      use_s3?: string[];
      aws_region?: string[];
      aws_bucket?: string[];
    };
  }

  interface IDatabaseReply extends IReply {
    errors?: {
      app_id?: string[];
      custom?: string[];
      driver?: string[];
      writer_host?: string[];
      writer_port?: string[];
      reader_host?: string[];
      reader_port?: string[];
      database?: string[];
    };
  }

  interface ICacheReply extends IReply {
    errors?: {
      app_id?: string[];
      custom?: string[];
      driver?: string[];
      host?: string[];
      port?: string[];
    };
  }

  interface IAppGroupReply extends IReply {
    errors?: {
      name?: string[];
      privileges?: string[];
      types?: string[];
      departments?: string[];
      searches?: string[];
      custom?: string[];
    };
  }

  interface IAppUserReply extends IReply {
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
  }

  interface IDepartmentReply extends IReply {
    errors?: {
      name?: string[];
    };
  }

  interface IDocumentTypeReply extends IReply {
    errors?: {
      name?: string[];
    };
  }

  interface ISearchReply extends IReply {
    errors?: {
      name?: string[];
      show_field?: string[];
    };
  }
}

export {};
