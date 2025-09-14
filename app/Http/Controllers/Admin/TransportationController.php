<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transportation;
use Illuminate\Http\Request;

class TransportationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transportations = Transportation::all();
        return view('admin.transportations', compact('transportations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.transportation-form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:transportations',
            'description' => 'nullable|string',
            'cost' => 'required|numeric|min:0',
        ]);

        Transportation::create($validated);

        return redirect()->route('admin.transportations.index')->with('success', 'Jenis transportasi berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transportation = Transportation::findOrFail($id);
        return view('admin.transportation-form', compact('transportation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $transportation = Transportation::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:transportations,name,' . $id,
            'description' => 'nullable|string',
            'cost' => 'required|numeric|min:0',
        ]);

        $transportation->update($validated);

        return redirect()->route('admin.transportations.index')->with('success', 'Jenis transportasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transportation = Transportation::findOrFail($id);
        $transportation->delete();

        return redirect()->route('admin.transportations.index')->with('success', 'Jenis transportasi berhasil dihapus.');
    }
}
