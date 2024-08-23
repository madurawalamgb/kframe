@extends('k-frame.layouts.app')

@section('content')
<div class="text-center p-6">
</div>
@endsection

@section('form')
    <div class="text-center mt-6">
        <h1 class="text-3xl font-bold">Welcome to the K-Frame Form Page</h1>

        <p class="text-lg mb-2">Using K-Frame you can build a form easily. Try it now.</p>

        <div class="mt-4 flex justify-center space-x-4 mb-4">
            <a href="{{ route('forms.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Get Started
            </a>
        </div>
    </div>
@endsection