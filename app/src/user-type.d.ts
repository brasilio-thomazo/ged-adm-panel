declare global {

  interface User {
    id: string;
    name: string;
    identity: string;
    role: string;
    phone: string;
    email: string;
    username: string;
    groups: Group[];
    created_at: string;
    updated_at: string;
  }

  interface UserRequest {
    name: string;
    identity: string;
    role: string;
    phone: string;
    email: string;
    username: string;
    password: string;
    password_confirmation: string;
    groups: number[];
  }

  interface UserError {
    message?: string;
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
}

export default {};