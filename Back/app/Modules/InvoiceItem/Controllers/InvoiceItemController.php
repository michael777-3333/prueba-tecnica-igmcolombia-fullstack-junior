<?php

namespace App\Modules\InvoiceItem\Controllers;

use App\Modules\InvoiceItem\Models\InvoiceItem;
use App\Modules\InvoiceItem\Services\InvoiceItemService;
use App\Modules\InvoiceItem\Resources\InvoiceItemResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceItemController extends Controller
{
    protected $InvoiceItemService;

    public function __construct(InvoiceItemService $InvoiceItemService)
    {
        $this->InvoiceItemService = $InvoiceItemService;
    }

    public function index()
    {
        $InvoiceItems = $this->InvoiceItemService->getAllInvoiceItems();
        return response()->json([
            'success' => true,
            'data' => InvoiceItemResource::collection($InvoiceItems),
            'message' => 'Items de factura encontrados exitosamente'
        ]);
    }

    public function store(Request $request)
    {
        $InvoiceItem = $this->InvoiceItemService->createInvoiceItem($request->all());
        return response()->json([
            'success' => true,
            'data' => new InvoiceItemResource($InvoiceItem),
            'message' => 'Item de factura creado exitosamente'
        ], 201);
    }

    public function show($id)
    {
        $InvoiceItem = InvoiceItem::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => new InvoiceItemResource($InvoiceItem),
            'message' => 'Item de factura encontrado exitosamente'
        ]);
    }

    public function update(Request $request, $id)
    {
        $InvoiceItem = InvoiceItem::findOrFail($id);
        $updatedItem = $this->InvoiceItemService->updateInvoiceItem($InvoiceItem, $request->all());
        return response()->json([
            'success' => true,
            'data' => new InvoiceItemResource($updatedItem),
            'message' => 'Item de factura actualizado exitosamente'
        ]);
    }

    public function destroy($id)
    {
        $InvoiceItem = InvoiceItem::findOrFail($id);
        $this->InvoiceItemService->deleteInvoiceItem($InvoiceItem);
        return response()->json([
            'success' => true,
            'message' => 'Item de factura eliminado exitosamente'
        ]);
    }
}