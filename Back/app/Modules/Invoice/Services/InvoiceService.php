<?php

namespace App\Modules\Invoice\Services;

use App\Modules\Invoice\Models\Invoice;
use App\Modules\InvoiceItem\Models\InvoiceItem;
use App\Modules\Item\Models\Item;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function getAllInvoices(): Collection
    {
        return Invoice::with(['customer', 'user', 'invoiceItems.item'])->get();
    }

    public function getInvoiceById(int $id): ?Invoice
    {
        return Invoice::with(['customer', 'user', 'invoiceItems.item'])->find($id);
    }

    public function getAllInvoicesFormatted(): SupportCollection
    {
        $invoices = Invoice::with(['customer', 'user', 'invoiceItems.item'])->get();

        return $invoices->map(function ($invoice) {
            return $this->formatInvoiceData($invoice);
        });
    }

    public function getInvoiceByIdFormatted(int $id): ?array
    {
        $invoice = Invoice::with(['customer', 'user', 'invoiceItems.item'])->find($id);

        return $invoice ? $this->formatInvoiceData($invoice) : null;
    }

    private function formatInvoiceData(Invoice $invoice): array
    {
        return [
            'id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'description' => $invoice->description,
            'notes' => $invoice->notes,
            'issued_date' => $invoice->issue_date,
            'due_date' => $invoice->due_date,
            'total_amount' => $invoice->total_amount,
            'status' => $invoice->status,
            'customer' => $invoice->customer ? [
                'id' => $invoice->customer->id,
                'first_name' => $invoice->customer->first_name,
                'last_name' => $invoice->customer->last_name,
                'document_type' => $invoice->customer->document_type,
                'document' => $invoice->customer->document,
                'email' => $invoice->customer->email,
                'phone' => $invoice->customer->phone,
            ] : null,
            'user' => $invoice->user ? [
                'id' => $invoice->user->id,
                'name' => $invoice->user->name,
                'email' => $invoice->user->email,
                'phone' => $invoice->user->phone,
                'position' => $invoice->user->position,
                'bio' => $invoice->user->bio,
            ] : null,
            'products' => $invoice->invoiceItems->map(function ($invoiceItem) {
                return [
                    'id' => $invoiceItem->item->id ?? null,
                    'name' => $invoiceItem->item->name ?? null,
                    'price' => $invoiceItem->item->price ?? null,
                    'quantity' => $invoiceItem->quantity ?? null,
                    'total' => $invoiceItem->total ?? null,
                    'iva' => $invoiceItem->tax_rate ?? $invoiceItem->item->iva ?? null,
                ];
            })->filter(function ($product) {
                return $product['id'] !== null;
            })->values()->toArray(),
            'created_at' => $invoice->created_at->toISOString(),
        ];
    }

    public function createInvoice(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {
            // Extraer los items del array de datos
            $items = $data['items'] ?? [];
            unset($data['items']); // Remover items del array principal

            // Crear la factura
            $invoice = Invoice::create($data);

            // Crear los invoice_items para cada item
            foreach ($items as $itemData) {
                // Buscar el item por product_id
                $item = Item::find($itemData['product_id']);

                if ($item) {
                    // Crear el invoice_item con la relación al item
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'item_id' => $item->id,
                        'name' => $item->name, // Usar el nombre del item
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $itemData['unit_price'],
                        'tax_rate' => 0.1, // Usar el IVA del item o 19% por defecto
                    ]);
                }
            }

            // Retornar la factura con sus relaciones cargadas
            return $invoice->load(['customer', 'user', 'invoiceItems.item']);
        });
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
