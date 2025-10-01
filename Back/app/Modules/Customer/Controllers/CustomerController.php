<?php

namespace App\Modules\Customer\Controllers;

use App\Modules\Customer\Models\Customer;
use App\Modules\Customer\Services\CustomerService;
use App\Modules\Customer\Resources\CustomerResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $CustomerService;

    public function __construct(CustomerService $CustomerService)
    {
        $this->CustomerService = $CustomerService;
    }

    public function index()
    {
        $Customers = $this->CustomerService->getAllCustomers();
        return response()->json([
            'success' => true,
            'data' => $Customers,
            'count' => $Customers->count()
        ]);
    }

    public function store(Request $request)
    {
        $Customer = $this->CustomerService->createCustomer($request->all());
        return response()->json([
            'success' => true,
            'data' => $Customer,
            'message' => 'Cliente creado exitosamente'
        ], 201);
    }

    public function show(Customer $Customer)
    {
        return response()->json([
            'success' => true,
            'data' => $Customer,
            'message' => 'Cliente encontrado exitosamente'
        ]);
    }

    public function update(Request $request, Customer $Customer)
    {
        $Customer = $this->CustomerService->updateCustomer($Customer, $request->all());
        return response()->json([
            'success' => true,
            'data' => $Customer,
            'message' => 'Cliente actualizado exitosamente'
        ]);
    }

    public function destroy(Customer $Customer)
    {
        $this->CustomerService->deleteCustomer($Customer);
        return response()->json(null, 204);
    }
}