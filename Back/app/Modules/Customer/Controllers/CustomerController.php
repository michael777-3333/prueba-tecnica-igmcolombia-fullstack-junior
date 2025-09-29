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
        return CustomerResource::collection($Customers);
    }

    public function store(Request $request)
    {
        $Customer = $this->CustomerService->createCustomer($request->all());
        return new CustomerResource($Customer);
    }

    public function show(Customer $Customer)
    {
        return new CustomerResource($Customer);
    }

    public function update(Request $request, Customer $Customer)
    {
        $Customer = $this->CustomerService->updateCustomer($Customer, $request->all());
        return new CustomerResource($Customer);
    }

    public function destroy(Customer $Customer)
    {
        $this->CustomerService->deleteCustomer($Customer);
        return response()->json(null, 204);
    }
}