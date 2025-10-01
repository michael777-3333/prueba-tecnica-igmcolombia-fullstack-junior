<?php

namespace App\Modules\Invoice\Services;

use App\Modules\Invoice\Models\Invoice;
use Illuminate\Database\Eloquent\Collection;

class InvoiceService
{
    public function getAllInvoices(): Collection
    {
        return Invoice::all();
    }

    public function getInvoiceById(int $id): ?Invoice
    {
        return Invoice::find($id);
    }

    public function createInvoice(array $data): Invoice
    {
        return Invoice::create($data);
    }

    public function updateInvoice(Invoice $Invoice, array $data): Invoice
    {
        $Invoice->update($data);
        return $Invoice->fresh();
    }

    public function deleteInvoice(Invoice $Invoice): bool
    {
        return $Invoice->delete();
    }

    // Add your business logic methods here
    // Example: 
    // public function getActiveInvoices(): Collection
    // {
    //     return Invoice::where('is_active', true)->get();
    // }
}