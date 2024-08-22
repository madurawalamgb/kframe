<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $items = Test::all();
        return view('tests.index', compact('items'));
    }

    public function create()
    {
        return view('tests.create');
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

        Test::create($request->all());

        return redirect()->route('tests.index');
    }
    
    public function show(Test $test)
    {
        return view('tests.view',compact('test'));
    }

    public function edit(Test $test)
    {
        return view('tests.edit', compact('test'));
    }

    public function update(Request $request, Test $test)
    {
        // $data = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'description' => 'nullable|string',
        //     'type' => 'required|string',
        //     'selections' => 'required|json',
        //     'readonly' => 'required|boolean',
        //     'disabled' => 'required|boolean',
        // ]);

        $test->update($request->all());

        return redirect()->route('tests.index');
    }

    public function destroy(Test $test)
    {
        $test->delete();

        return redirect()->route('tests.index');
    }
}