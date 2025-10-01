<?php

namespace App\Modules\Item\Services;

use App\Modules\Item\Models\Item;
use Illuminate\Database\Eloquent\Collection;

class ItemService
{
    public function getAllItems(): Collection
    {
        return Item::all();
    }

    public function getItemById(int $id): ?Item
    {
        return Item::find($id);
    }

    public function createItem(array $data): Item
    {
        return Item::create($data);
    }

    public function updateItem(Item $Item, array $data): Item
    {
        $Item->update($data);
        return $Item->fresh();
    }

    public function deleteItem(Item $Item): bool
    {
        return $Item->delete();
    }

    // Add your business logic methods here
    // Example: 
    // public function getActiveItems(): Collection
    // {
    //     return Item::where('is_active', true)->get();
    // }
}