<?php

namespace App\Http\Controllers\Admin;

use App\CustomerAffairs\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomersController extends Controller
{

    public function index()
    {
        return Customer::orderBy('name')->get()->map->toArray();
    }

    public function show(Customer $customer)
    {
        return $customer->toArray();
    }

    public function store()
    {
        request()->validate([
            'name' => ['required'],
            'email' => ['required_without:phone', 'email'],
            'phone' => ['required_without:email'],
        ]);

        Customer::create(request()->only('name', 'email', 'phone'));
    }

    public function update(Customer $customer)
    {
        request()->validate([
            'name' => ['required'],
            'email' => ['required_without:phone', 'email'],
            'phone' => ['required_without:email'],
        ]);

        $customer->update(request()->only('name', 'email', 'phone'));
    }

    public function delete(Customer $customer)
    {
        $customer->delete();
    }
}
