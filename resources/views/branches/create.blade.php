@extends('layout')

@section('title', 'Add Branch')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Add New Branch</h1>
            <a href="{{ route('branches.index') }}" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-gray-400 to-gray-500 text-white hover:from-gray-500 hover:to-gray-600 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2">
                <i class="bi bi-arrow-left"></i>
                <span class="font-medium">Back to Branches</span>
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p><strong>Whoops! Something went wrong!</strong></p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg p-6">
            <form action="{{ route('branches.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="branchName" class="block text-gray-700 text-sm font-bold mb-2">Branch Name:</label>
                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="branchName" name="branchName" value="{{ old('branchName') }}" required>
                </div>
                <div class="mb-6">
                    <label for="judge_name" class="block text-gray-700 text-sm font-bold mb-2">Judge Name (Optional):</label>
                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="judge_name" name="judge_name" value="{{ old('judge_name') }}">
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 text-white hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2">
                        <i class="bi bi-save"></i>
                        <span class="font-medium">Add Branch</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection 