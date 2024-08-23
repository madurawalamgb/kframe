<?php

namespace App\Http\Controllers;

use App\Models\Reach;
use Illuminate\Http\Request;

class ReachController extends Controller
{
    public function index()
    {
        $items = Reach::all();
        return view('reaches.index', compact('items'));
    }

    public function create()
    {
        return view('reaches.create');
    }

    public function store(Request $request)
    {
        // $data = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'description' => 'nullable|string',
        //     'type' => 'required|string',
        //     'selections' => 'required|json',
        //     'readonly' => 'required|boolean',
        //     'disabled' => 'required|boolean',
        // ]);

        Reach::create($request->all());

        return redirect()->route('reaches.index');
    }
    
    public function show(Reach $reach)
    {
        return view('reaches.view',compact('reach'));
    }

    public function edit(Reach $reach)
    {
        return view('reaches.edit', compact('reach'));
    }

    public function update(Request $request, Reach $reach)
    {
        // $data = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'description' => 'nullable|string',
        //     'type' => 'required|string',
        //     'selections' => 'required|json',
        //     'readonly' => 'required|boolean',
        //     'disabled' => 'required|boolean',
        // ]);

        $reach->update($request->all());

        return redirect()->route('reaches.index');
    }

    public function destroy(Reach $reach)
    {
        $reach->delete();

        return redirect()->route('reaches.index');
    }
}