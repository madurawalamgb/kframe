@extends('k-frame.layouts.app')

@section('content')
    <div class="text-center">
        <h1 class="text-3xl font-bold">Welcome to the K-Frame Form Page</h1>
        <div class="mt-4 flex justify-center space-x-4 mb-4">
            @if($type=='view' || $type=='create')
                <a href="{{ route('forms.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    All Forms
                </a>

                @if($type=='view')
                    <form id="generate_form" action="{{ route('forms.generate',[$form->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Genarate Form
                        </button>
                    </form>
                    <a href="{{ route('forms.edit', $form->id) }}" class="inline-flex items-center px-4 py-2 ml-4 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        Edit
                    </a>
                @endif
            @endif
        </div>

        @if($editable)
            <p class="text-lg mb-2">Fill out the form below:</p>
        @endif
    </div>

    
@endsection

@section('form')

    <form id="mofify_form" action="{{$type == 'edit' && isset($form) ? route('forms.update',[$form->id]) :  route('forms.store') }}" method="POST" class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-md">
        @csrf
        @if($type == 'edit')
            @method('PUT')
        @endif
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name" value="{{old('name')??$form->name??null}}" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" {{!$editable?' disabled':''}}>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" {{!$editable?' disabled':''}}>{{old('description')??$form->description??null}}</textarea>
        </div>

        <div class="mb-4">
            <label for="dependencies" class="block text-sm font-medium text-gray-700">Dependencies</label>
            <select id="dependencies" name="dependencies[]" multiple class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" {{!$editable?' disabled':''}}>
                @foreach(getModelClassNames() as $model)
                    <option value="{{ $model }}" {{in_array($model,old('dependencies')??$form->dependencies??[])? 'selected ':''}}>{{ $model }}</option>
                @endforeach
            </select>
        </div>
        @if($editable)
        <div class="mt-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Save
            </button>
        </div>          
        @endif
    </form>
    <div class="mb-4"></div>
    @if($type != 'create')
    <form id="modify_field" action="{{ route('fields.store') }}" method="POST" class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-md">
    @csrf
    <input type="hidden" name="form_id" value="{{$form->id??null}}">

    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Field</label>
        <input type="text" id="field" name="field" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
    </div>
    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" id="name" name="name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
    </div>

    <div class="mb-4">
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea id="description" name="description" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
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
        <label for="selections" class="block text-sm text-gray-700 font-medium mb-2">selections (JSON format):</label>
        <textarea id="selections" name="selections" rows="4" cols="30"
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

    <div class="mt-4">
        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Save
        </button>
    </div>          
</form>

<div class="mt-8 max-w-lg mx-auto">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Name
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Description
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Type
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Selections
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Readonly
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Disabled
                    </th>
                </tr>
            </thead>
            <tbody>
            @if(isset($form) && isset($form->fields))
                @foreach ($form->fields as $item)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $item->name }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $item->description }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $item->type }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ json_encode($item->selections , JSON_PRETTY_PRINT)}}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $item->readonly ? 'Yes' : 'No' }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $item->disabled ? 'Yes' : 'No' }}
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>

@endif
@endsection