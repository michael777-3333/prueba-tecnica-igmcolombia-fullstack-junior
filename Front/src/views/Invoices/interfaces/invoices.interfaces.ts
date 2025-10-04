import { Customer } from "@/views/custormers/interfaces/customer.interface"
import { Product } from "@/views/products/interfaces/products.interface"
import { User } from "@/views/users/interfaces/user.interface"

export interface Invoice {
  id: number
  invoice_number: string
  description: string
  notes: string
  issued_date: string
  due_date: string
  total_amount: number
  status: string
  customer: Customer
  user: User
  products: Product[]
  created_at: string
}

export interface CreateInvoiceData {
  invoice_number: string
  description: string
  notes: string
  issued_date: string
  due_date: string
  total_amount: number
  status: string
  customer: string
  user: string
  products: string[]
}

export interface UpdateInvoiceData {
  invoice_number: string
  description: string
  notes: string
  issued_date: string
  due_date: string
  total_amount: number
  status: string
  customer: string
  user: string
  products: string[]
}
