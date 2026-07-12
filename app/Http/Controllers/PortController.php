<?php

namespace App\Http\Controllers;

use App\Models\Port;
use App\Models\Country;
use Illuminate\Http\Request;

class PortController extends Controller
{
    public function index()
    {
        $ports = Port::with('country')->paginate(10);

        return view('ports.index', compact('ports'));
    }

    public function create()
    {
        $countries = Country::orderBy('country_name')->get();

        return view('ports.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'port_name' => 'required',
        ]);

        Port::create($request->all());

        return redirect()->route('ports.index')
            ->with('success', 'Port berhasil ditambahkan.');
    }

    public function show(Port $port)
    {
        return view('ports.show', compact('port'));
    }

    public function edit(Port $port)
    {
        $countries = Country::orderBy('country_name')->get();

        return view('ports.edit', compact('port', 'countries'));
    }

    public function update(Request $request, Port $port)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'port_name' => 'required',
        ]);

        $port->update($request->all());

        return redirect()->route('ports.index')
            ->with('success', 'Port berhasil diperbarui.');
    }

    public function destroy(Port $port)
    {
        $port->delete();

        return redirect()->route('ports.index')
            ->with('success', 'Port berhasil dihapus.');
    }
}