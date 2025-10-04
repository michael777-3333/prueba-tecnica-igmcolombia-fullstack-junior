export interface Customer {
    id: number
    first_name: string
    last_name: string
    email: string
    phone: string
    document: string
    document_type: string
    created_at: string
    updated_at: string
}

export interface CreateCustomerData {
    first_name: string
    last_name: string
    email: string
    phone: string
    document: string
    document_type: string
}

export interface UpdateCustomerData {
    first_name: string
    last_name: string
    email: string
    phone: string
    document: string
    document_type: string
}
