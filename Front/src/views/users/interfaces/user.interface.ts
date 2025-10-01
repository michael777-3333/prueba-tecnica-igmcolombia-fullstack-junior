export interface User {
  id: number
  name: string
  email: string
  phone?: string
  position?: string
  bio?: string
  created_at: string
  updated_at: string
}

export interface CreateUserData {
  name: string
  email: string
  password: string
}

export interface UpdateUserData {
  id: number
  name: string
  email: string
  password: string
}
