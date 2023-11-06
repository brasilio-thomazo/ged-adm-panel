declare global {
  interface Privilege {
    group?: Permission;
    user?: Permission;
    client?: Permission;
    app?: Permission;
    authorities: string[];
  }

  interface PrivilegeRequest {
    group?: Permission;
    user?: Permission;
    client?: Permission;
    app?: Permission;
  }

  /**
   * Group type define
   */
  interface Group {
    id: number;
    name: string;
    is_admin: boolean;
    privileges: Privilege;
    created_at: string;
    updated_at: string;
  }

  interface GroupRequest {
    name: string;
    privileges: PrivilegeRequest;
  }

  interface GroupError {
    message?: string;
    errors?: {
      name?: string[];
      privileges?: string[];
    };
  }
}

export { }