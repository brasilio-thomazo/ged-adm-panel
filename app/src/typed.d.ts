declare global {
  type IProfileRequest = {
    name: string;
    identity: string;
    role: string;
    phone: string;
    email: string;
  };

  type IPasswordRequest = {
    current_password: string;
    password: string;
    password_confirmation: string;
  };

  type IProfileReply = {
    message?: string;
    errors?: {
      name?: string[];
      identity?: string[];
      role?: string[];
      phone?: string[];
      email?: string[];
    };
  };

  type IPasswordReply = {
    message?: string;
    errors?: {
      current_password?: string[];
      password?: string[];
      password_confirmation?: string[];
    };
  };

  type SlotType = 'create' | 'edit' | 'show' | 'list' | 'error';
  /**
   * Authentication type define
   */
  interface AuthRequest {
    username: string;
    password: string;
    remember?: boolean;
  }

  interface AuthError {
    message?: string;
    errors?: {
      username?: string[];
      password?: string[];
    };
  }
  interface AuthReply {
    user: User;
    token: string;
  }

  /**
   * Privilege type deine
   */
  type Permission = 'r' | 'rw' | undefined;
  /**
   * Client type define
   */
  interface Client {
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

  interface ClientRequest {
    name: string;
    identity: string;
    email: string;
    phone: string;
    scope: 'client' | 'provider';
    manager: string;
    role: string;
  }

  interface ClientError {
    message?: string;
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
}

export default {};
