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
        return InvoiceItemResource::collection($InvoiceItems);
    }

    public function store(Request $request)
    {
        $InvoiceItem = $this->InvoiceItemService->createInvoiceItem($request->all());
        return new InvoiceItemResource($InvoiceItem);
    }

    public function show(InvoiceItem $InvoiceItem)
    {
        return new InvoiceItemResource($InvoiceItem);
    }

    public function update(Request $request, InvoiceItem $InvoiceItem)
    {
        $InvoiceItem = $this->InvoiceItemService->updateInvoiceItem($InvoiceItem, $request->all());
        return new InvoiceItemResource($InvoiceItem);
    }

    public function destroy(InvoiceItem $InvoiceItem)
    {
        $this->InvoiceItemService->deleteInvoiceItem($InvoiceItem);
        return response()->json(null, 204);
    }
}