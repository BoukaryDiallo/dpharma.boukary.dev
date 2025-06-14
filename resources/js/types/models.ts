export interface Category {
    id: number;
    name: string;
}

export interface PharmaceuticalProduct {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    category_id: number | null;
    category?: Category | null;
    form: string;
    dosage: string;
    price: number;
    stock_quantity: number;
    expiration_date: string;
    manufacturer: string;
    batch_number: string;
    requires_prescription: boolean;
    is_active: boolean;
    created_at: string;
    updated_at: string;
    deleted_at: string | null;
}

export interface PaginationMeta {
    current_page: number;
    from: number;
    last_page: number;
    path: string;
    per_page: number;
    to: number;
    total: number;
}

export interface PaginatedResponse<T> {
    data: T[];
    links: {
        first: string | null
        last: string | null
        prev: string | null
        next: string | null
    };
    meta: PaginationMeta;
}

export interface ProductFormData {
    name: string;
    description: string;
    price: number;
    quantity: number;
    expiry_date: string;
    manufacturer: string;
    batch_number: string;
    category_id?: number | null;
}

export interface ProductsPageProps {
    products: PaginatedResponse<PharmaceuticalProduct>;
    filters: {
        search?: string
        per_page?: number | string
    };
}
