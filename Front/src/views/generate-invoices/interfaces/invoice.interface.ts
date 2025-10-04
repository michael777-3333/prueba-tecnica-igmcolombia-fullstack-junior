export interface Client {
  id: number
  first_name: string
  last_name: string
  name?: string // Para compatibilidad con versiones anteriores
  email: string
  phone?: string
  address?: string
  created_at: string
  updated_at: string
}

export interface Product {
  id: number
  name: string
  description?: string
  price: number
  stock: number
  created_at: string
  updated_at: string
}

export interface InvoiceItem {
  id?: number
  product_id: number
  product_name: string
  quantity: number
  unit_price: number
  total: number
}

export interface Invoice {
  id: number
  invoice_number: string
  client_id: number
  client: Client
  items: InvoiceItem[]
  subtotal: number
  tax: number
  total_amount: number
  status: string
  notes?: string
  created_at: string
  updated_at: string
}

export interface CreateInvoiceData {
  customer_id: number
  user_id: number
  invoice_number: string
  issue_date: string
  due_date: string
  total_amount: number
  status: string
  description: string
  notes?: string
  items: {
    product_id: number
    quantity: number
    unit_price: number
    total: number
    tax_rate?: number
  }[]
}
