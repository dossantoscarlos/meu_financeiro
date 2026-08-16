<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreCaixinhaRequest;
use App\Http\Requests\UpdateCaixinhaRequest;
use App\Models\Caixinha;

class CaixinhaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCaixinhaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Caixinha $caixinha)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Caixinha $caixinha)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCaixinhaRequest $request, Caixinha $caixinha)
    {
        $caixinha->update($request->validated());

        return redirect()->back()
            ->with('success', 'Caixinha atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Caixinha $caixinha)
    {
        //
    }
}
