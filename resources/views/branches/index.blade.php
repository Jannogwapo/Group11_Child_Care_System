@extends('layout')

@section('title', 'Manage Branches')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Manage Branches</h1>
            <a href="{{ route('branches.create') }}" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 text-white hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2">
                <i class="bi bi-plus-lg"></i>
                <span class="font-medium">Add Branch</span>
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg p-6">
            @if($branches->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Branch Name</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judge Name</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($branches as $branch)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-4 border-b border-gray-200 whitespace-nowrap">{{ $branch->branchName }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 whitespace-nowrap">{{ $branch->judge_name ?? 'N/A' }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200 whitespace-nowrap">
                                        <a href="{{ route('branches.edit', $branch) }}" class="text-blue-600 hover:underline mr-3">Edit</a>
                                        <form action="{{ route('branches.destroy', $branch) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this branch?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">No branches found.</p>
            @endif
        </div>
    </div>
@endsection 