@extends('k-frame.layouts.app')

@section('content')
<div class="text-center p-6">
</div>
@endsection

@section('form')
<div class="container mx-auto p-6 bg-white rounded-lg shadow-md max-w-lg">
        <h1 class="text-center text-xl font-bold mb-4">View Reach - {{$reach->id}}</h1>
        @include('reaches.form', ['editable' => false])

    <div class="mt-4 flex ">
        <a href="{{ route('reaches.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            List
        </a>
        <a href="{{ route('reaches.edit', $reach->id) }}" class="inline-flex items-center px-4 py-2 ml-4 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
            Edit
        </a>
    </div>
</div>
@endsection