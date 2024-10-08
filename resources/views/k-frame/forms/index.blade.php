@extends('k-frame.layouts.app')

@section('content')
    <div class="text-center p-6">
    </div>
@endsection

@section('form')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-center text-3xl font-bold text-gray-900 mb-6">K-Frame form List</h1>
    <div class="mb-4">
        <a href="{{ route('forms.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Create
        </a>
    </div>
    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class='px-4 py-2 text-left text-sm font-medium text-gray-500'>ID</th>
                    <th class='px-4 py-2 text-left text-sm font-medium text-gray-500'>Name</th>
                    <th class='px-4 py-2 text-left text-sm font-medium text-gray-500'>Description</th>
                    <th class='px-4 py-2 text-left text-sm font-medium text-gray-500'>Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($forms as $form)
                <tr>
                    <td class='px-4 py-2 text-sm text-gray-900'>{{ $form->id }}</td>
                    <td class='px-4 py-2 text-sm text-gray-900'>{{ $form->name }}</td>
                    <td class='px-4 py-2 text-sm text-gray-900'>{{ $form->description }}</td>

                    <td class='px-4 py-2 text-sm text-gray-900'>
                        <a href="{{ route('forms.show', $form->id) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            View
                        </a>
                        <a href="{{ route('forms.edit', $form->id) }}" class="inline-flex items-center px-3 py-1 ml-4 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            Edit
                        </a>
                        <form action="{{ route('forms.destroy', $form->id) }}" method="POST" class="inline-block ml-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Delete
                            </button>
                        </form>
                        @if( Route::has($form->table.'.index'))
                        <a href="{{route($form->table.'.index') }}" class="inline-flex items-center px-3 py-1 ml-4 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Form
                        </a>
                        @endif
                        <!-- <a href="{{ route('forms.edit', $form->id) }}" class="inline-flex items-center px-3 py-1 ml-4 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            Edit
                        </a> -->
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection