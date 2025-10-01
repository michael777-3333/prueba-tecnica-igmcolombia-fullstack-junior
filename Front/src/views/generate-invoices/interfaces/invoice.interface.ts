export interface Client {
  id: number
  name: string
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
  id: number
  product_id: number
  product_name: string
  quantity: number
  unit_price: number
  total: number
}

export interface Invoice {
  id?: number
  client_id: number
  client_name?: string
  items: InvoiceItem[]
  subtotal: number
  tax: number
  total: number
  status: 'draft' | 'sent' | 'paid' | 'cancelled'
  notes?: string
  created_at?: string
  updated_at?: string
}

export interface CreateInvoiceData {
  client_id: number
  items: Omit<InvoiceItem, 'id' | 'product_name'>[]
  notes?: string
}
