<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;

class FieldController extends Controller
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
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'form_id' => 'required|exists:forms,id',
            'field' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:TEXT,NUMBER,TEXTAREA,BELONGSTO,BELONGSTOMANY,SELECTON,FUNCTION,HASONE,MULTYSELECTON,BOOLEAN,BUTTON',
            'selections' => 'nullable|string',
            'readonly' => 'nullable|boolean',
            'disabled' => 'nullable|boolean',
        ]);
        Field::create($validatedData);

        return redirect()->back()->with('success', 'Record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Field $field)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Field $field)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Field $field)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Field $field)
    {
        //
    }
}
