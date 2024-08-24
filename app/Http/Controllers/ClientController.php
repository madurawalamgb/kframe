<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    private $booleanFields = [];

    public function __construct()
    {
        $this->booleanFields = ['higher_than'];
    }

    public function index()
    {
        $items = Client::all();
        return view('clients.index', compact('items'));
    }

    public function create()
    {
        return view('clients.create');
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

        
       
        $data = $request->all();
        foreach($this->booleanFields as $field){
            $data[$field] = isset($data[$field]) ? true : false;
        }
        Client::create($data);

        return redirect()->route('clients.index');
    }
    
    public function show(Client $client)
    {
        return view('clients.view',compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        // $data = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'description' => 'nullable|string',
        //     'type' => 'required|string',
        //     'selections' => 'required|json',
        //     'readonly' => 'required|boolean',
        //     'disabled' => 'required|boolean',
        // ]);

        $data = $request->all();
        foreach($this->booleanFields as $field){
            $data[$field] = isset($data[$field]) ? true : false;
        }

        $client->update($data);

        return redirect()->route('clients.index');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index');
    }
}