<?php

namespace App\Modules\Item\Controllers;

use App\Modules\Item\Models\Item;
use App\Modules\Item\Services\ItemService;
use App\Modules\Item\Resources\ItemResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    protected $ItemService;

    public function __construct(ItemService $ItemService)
    {
        $this->ItemService = $ItemService;
    }

    public function index()
    {
        $Items = $this->ItemService->getAllItems();
        return response()->json([
            'success' => true,
            'data' => $Items,
            'message' => 'Items encontrados exitosamente'
        ]);
    }

    public function store(Request $request)
    {
        $Item = $this->ItemService->createItem($request->all());
        return response()->json([
            'success' => true,
            'data' => $Item,
            'message' => 'Item creado exitosamente'
        ], 201);
    }

    public function show(Item $Item)
    {
        return response()->json([
            'success' => true,
            'data' => $Item,
            'message' => 'Item encontrado exitosamente'
        ]);
    }

    public function update(Request $request, Item $Item)
    {
        $Item = $this->ItemService->updateItem($Item, $request->all());
        return response()->json([
            'success' => true,
            'data' => $Item,
            'message' => 'Item actualizado exitosamente'
        ]);
    }

    public function destroy(Item $Item)
    {
        $this->ItemService->deleteItem($Item);
        return response()->json(null, 204);
    }
}