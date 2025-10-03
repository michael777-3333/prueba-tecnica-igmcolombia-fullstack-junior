export interface Product {
  id: number
  name: string
  quantity: number
  price: number
  total: number
  iva: number
  created_at: string
  updated_at: string
}

export interface CreateProductData {
  name: string
  quantity: number
  price: number
  total: number
  iva: number
}

export interface UpdateProductData {
  name: string
  quantity: number
  price: number
  total: number
  iva: number
}
