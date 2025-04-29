<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    public function index()
    {
        return Fornecedor::all();
    }

    public function store(Request $request)
    {
        return Fornecedor::create($request->all());
    }

    public function show(Fornecedor $fornecedor)
    {
        return $fornecedor;
    }

    public function update(Request $request, Fornecedor $fornecedor)
    {
        $fornecedor->update($request->all());
        return $fornecedor;
    }

    public function destroy(Fornecedor $fornecedor)
    {
        $fornecedor->delete();
        return response()->noContent();
    }
}