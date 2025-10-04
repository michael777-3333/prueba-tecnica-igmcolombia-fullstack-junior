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
        $Invoice = $this->InvoiceService->createInvoice($request->all());
        return response()->json([
            'success' => true,
            'data' => $Invoice,
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
