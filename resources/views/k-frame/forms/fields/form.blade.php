@extends('k-frame.layouts.app')

@section('content')
    <div class="text-center">
        <h1 class="text-3xl font-bold">Welcome to the Form Page</h1>
        <p class="text-lg">Fill out the form below:</p>
    </div>
@endsection

@section('form')

        <form action="{{ route('fields.store') }}" method="POST" class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-md">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
        </div>

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        
        <div class="mb-4">
            <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
            <select id="type" name="type" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="TEXT">TEXT</option>
                <option value="NUMBER">NUMBER</option>
                <option value="TEXTAREA">TEXTAREA</option>
                <option value="BELONGSTO">BELONGSTO</option>
                <option value="BELONGSTOMANY">BELONGSTOMANY</option>
                <option value="SELECTON">SELECTON</option>
                <option value="FUNCTION">FUNCTION</option>
                <option value="HASONE">HASONE</option>
                <option value="MULTYSELECTON">MULTYSELECTON</option>
                <option value="BOOLEAN">BOOLEAN</option>
                <option value="BUTTON">BUTTON</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="dependencies" class="block text-sm font-medium text-gray-700">Related To</label>
            <select id="related_model" name="related_model" multiple class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" {{!$editable?' disabled':''}}>
                @foreach(getModelClassNames() as $model)
                    <option value="{{ $model }}" {{in_array($model,old('dependencies')??$form->related_model??[])? 'selected ':''}}>{{ $model }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="selections" class="block text-sm text-gray-700 font-medium mb-2">selections (JSON format):</label>
            <textarea id="selections" name="selections" rows="10" cols="30"
                  class="w-full p-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                  placeholder='{"M": "Male", "F": "Female"}'>{{ old('selections') }}</textarea>
        </div>

        <div class="mb-4">
            <div class="flex items-center">
                <input type="checkbox" id="readonly" name="readonly" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="readonly" class="ml-2 block text-sm font-medium text-gray-700">Readonly</label>
            </div>
        </div>

        <div class="mb-4">
            <div class="flex items-center">
                <input type="checkbox" id="disabled" name="disabled" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="disabled" class="ml-2 block text-sm font-medium text-gray-700">Disabled</label>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Submit
            </button>
        </div>
    </form>
@endsection