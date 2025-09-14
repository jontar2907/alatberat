<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    public function create()
    {
        return view('admin.operator-form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:operators,email',
            'address' => 'nullable|string',
        ]);

        Operator::create($validated);

        return redirect()->route('admin.operators')->with('success', 'Operator alat berat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $operator = Operator::findOrFail($id);
        return view('admin.operator-form', compact('operator'));
    }

    public function update(Request $request, $id)
    {
        $operator = Operator::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:operators,email,' . $id,
            'address' => 'nullable|string',
        ]);

        $operator->update($validated);

        return redirect()->route('admin.operators')->with('success', 'Operator alat berat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $operator = Operator::findOrFail($id);
        $operator->delete();

        return redirect()->route('admin.operators')->with('success', 'Operator alat berat berhasil dihapus.');
    }
}
