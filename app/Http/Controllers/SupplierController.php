<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Country;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with('country')->paginate(10);

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        $countries = Country::orderBy('country_name')->get();

        return view('suppliers.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'company_name' => 'required',
            'email' => 'nullable|email',
        ]);

        Supplier::create($request->all());

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function show(Supplier $supplier)
    {
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        $countries = Country::orderBy('country_name')->get();

        return view('suppliers.edit', compact('supplier', 'countries'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'company_name' => 'required',
            'email' => 'nullable|email',
        ]);

        $supplier->update($request->all());

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }
}