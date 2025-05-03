@extends('layout')
@section('title', 'Access Log')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">User Management</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg p-6">
        @foreach($usersByRole as $role => $users)
            <h2 class="font-bold text-xl mb-4 mt-8">{{ strtoupper($role) }}</h2>
            @foreach($users as $user)
                <div class="flex items-center mb-4">
                    <div>
                        <div class="font-semibold">{{ $user->name }}</div>
                        <div class="text-sm text-gray-500">
                            <span class="{{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                                ● {{ $user->is_active ? 'Active' : 'Disabled' }}
                            </span>
                        </div>
                    </div>
                    <!-- Actions here -->
                </div>
            @endforeach
        @endforeach
    </div>
    <div class="mt-4">
        {{-- {{ $users->links() }} --}}
    </div>
</div>
@endsection

    
