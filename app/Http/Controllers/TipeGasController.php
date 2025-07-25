<?php

namespace App\Http\Controllers;

use App\Models\TipeGas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipeGasController extends Controller
{
    public function index()
    {
        $tipeGas = TipeGas::orderBy('created_at', 'desc')->get();
        return view('tipe-gas.index', compact('tipeGas'));
    }

    public function create()
    {
        return view('tipe-gas.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:tipe_gas,nama',
        ], [
            'nama.required' => 'Nama tipe gas wajib diisi',
            'nama.unique' => 'Nama tipe gas sudah ada',
            'nama.max' => 'Nama tipe gas maksimal 255 karakter'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            TipeGas::create([
                'nama' => $request->nama
            ]);

            return redirect()->route('tipe-gas.index')
                ->with('success', 'Tipe gas berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan tipe gas: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $tipeGas = TipeGas::findOrFail($id);
        return view('tipe-gas.show', compact('tipeGas'));
    }

    public function edit($id)
    {
        $tipeGas = TipeGas::findOrFail($id);
        return view('tipe-gas.edit', compact('tipeGas'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:tipe_gas,id',
            'nama' => 'required|string|max:255|unique:tipe_gas,nama,' . $request->id,
        ], [
            'nama.required' => 'Nama tipe gas wajib diisi',
            'nama.unique' => 'Nama tipe gas sudah ada',
            'nama.max' => 'Nama tipe gas maksimal 255 karakter'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $tipeGas = TipeGas::findOrFail($request->id);
            $tipeGas->update([
                'nama' => $request->nama
            ]);

            return redirect()->route('tipe-gas.index')
                ->with('success', 'Tipe gas berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui tipe gas: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $tipeGas = TipeGas::findOrFail($id);
            
            // Check if tipe gas is being used
            if ($tipeGas->stokGas()->count() > 0) {
                return redirect()->route('tipe-gas.index')
                    ->with('error', 'Tipe gas tidak dapat dihapus karena masih digunakan dalam stok');
            }

            $tipeGas->delete();

            return redirect()->route('tipe-gas.index')
                ->with('success', 'Tipe gas berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('tipe-gas.index')
                ->with('error', 'Gagal menghapus tipe gas: ' . $e->getMessage());
        }
    }
}
