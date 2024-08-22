@extends('k-frame.layouts.app')

@section('content')
<div class="text-center p-6">
</div>
@endsection

@section('form')
<div class="container">
    <form action="{{ route('tests.update', $test->id) }}" method="POST" class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-md">
        @csrf
        @method('PUT')
        <h1 class="text-center text-xl font-bold mb-4">Edit Test - {{$test->id}}</h1>
        @include('tests.form', ['editable' => true])
        <div class="mt-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Save
            </button>
        </div>                
    </form>
</div>
@endsection