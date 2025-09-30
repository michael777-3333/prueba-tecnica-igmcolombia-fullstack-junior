<?php

namespace App\Modules\InvoiceItem\Services;

use App\Modules\InvoiceItem\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Collection;

class InvoiceItemService
{
    public function getAllInvoiceItems(): Collection
    {
        return InvoiceItem::all();
    }

    public function getInvoiceItemById(int $id): ?InvoiceItem
    {
        return InvoiceItem::find($id);
    }

    public function createInvoiceItem(array $data): InvoiceItem
    {
        return InvoiceItem::create($data);
    }

    public function updateInvoiceItem(InvoiceItem $InvoiceItem, array $data): InvoiceItem
    {
        $InvoiceItem->update($data);
        return $InvoiceItem;
    }

    public function deleteInvoiceItem(InvoiceItem $InvoiceItem): bool
    {
        return $InvoiceItem->delete();
    }

    // Add your business logic methods here
    // Example: 
    // public function getActiveInvoiceItems(): Collection
    // {
    //     return InvoiceItem::where('is_active', true)->get();
    // }
}