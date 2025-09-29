<?php

namespace App\Modules\Customer\Services;

use App\Modules\Customer\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

class CustomerService
{
    public function getAllCustomers(): Collection
    {
        return Customer::all();
    }

    public function getCustomerById(int $id): ?Customer
    {
        return Customer::find($id);
    }

    public function createCustomer(array $data): Customer
    {
        return Customer::create($data);
    }

    public function updateCustomer(Customer $Customer, array $data): Customer
    {
        $Customer->update($data);
        return $Customer->fresh();
    }

    public function deleteCustomer(Customer $Customer): bool
    {
        return $Customer->delete();
    }

    // Add your business logic methods here
    // Example: 
    // public function getActiveCustomers(): Collection
    // {
    //     return Customer::where('is_active', true)->get();
    // }
}