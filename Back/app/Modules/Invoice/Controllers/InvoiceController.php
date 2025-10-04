<?php

namespace App\Modules\Invoice\Controllers;

use App\Modules\Invoice\Models\Invoice;
use App\Modules\Invoice\Services\InvoiceService;
use App\Modules\Invoice\Resources\InvoiceResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    protected $InvoiceService;

    public function __construct(InvoiceService $InvoiceService)
    {
        $this->InvoiceService = $InvoiceService;
    }

    public function index()
    {
        $Invoices = $this->InvoiceService->getAllInvoicesFormatted();
        return response()->json([
            'success' => true,
            'data' => $Invoices,
            'count' => $Invoices->count()
        ]);
    }

    public function store(Request $request)
    {
        // Validar los datos de entrada
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'required|exists:users,id',
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after:issue_date',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid,overdue',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'required|numeric|min:0',
        ]);

        $Invoice = $this->InvoiceService->createInvoice($validatedData);
        $formattedInvoice = $this->InvoiceService->getInvoiceByIdFormatted($Invoice->id);

        return response()->json([
            'success' => true,
            'data' => $formattedInvoice,
            'message' => 'Factura creada exitosamente'
        ], 201);
    }

    public function show(Invoice $Invoice)
    {
        $formattedInvoice = $this->InvoiceService->getInvoiceByIdFormatted($Invoice->id);
        return response()->json([
            'success' => true,
            'data' => $formattedInvoice,
            'message' => 'Factura encontrada exitosamente'
        ]);
    }

    public function update(Request $request, Invoice $Invoice)
    {
        $Invoice = $this->InvoiceService->updateInvoice($Invoice, $request->all());
        return response()->json([
            'success' => true,
            'data' => $Invoice,
            'message' => 'Factura actualizada exitosamente'
        ]);
    }

    public function destroy(Invoice $Invoice)
    {
        $this->InvoiceService->deleteInvoice($Invoice);
        return response()->json(null, 204);
    }
}
