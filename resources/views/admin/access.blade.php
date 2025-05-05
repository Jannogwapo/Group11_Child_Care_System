@extends('layout')
@section('title', 'Access Management')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">User Access Management</h1>
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
        <!-- Admin Users -->
        <h2 class="font-bold text-xl mb-4 mt-8">ADMIN USERS</h2>
        <div class="space-y-4">
            @foreach($usersByRole['admin'] ?? [] as $user)
                <div class="flex items-center justify-between p-4 border rounded-lg">
                    <div class="flex items-center space-x-4">
                        <div>
                            <div class="font-semibold">{{ $user->name }}</div>
                            <div class="text-sm text-gray-500">
                                <span class="{{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                                    ● {{ $user->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @if(auth()->user()->id !== $user->id)
                        <form action="{{ route('admin.toggle-user', $user->id) }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-3 py-1 rounded {{ $user->is_active ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white">
                                {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    </div>


    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Social Worker Users -->
        <h2 class="font-bold text-xl mb-4 mt-8">SOCIAL WORKER USERS</h2>
        <div class="space-y-4">
            @foreach($usersByRole['social_worker'] ?? [] as $user)
                <div class="flex items-center justify-between p-4 border rounded-lg">
                    <div class="flex items-center space-x-4">
                        <div>
                            <div class="font-semibold">{{ $user->name }}</div>
                            <div class="text-sm text-gray-500">
                                <span class="{{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                                    ● {{ $user->is_active ?  'Disabled' : 'Active' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <form action="{{ route('admin.toggle-user', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-black rounded">
                                {{ $user->access_id === 1 ? 'Disable' : 'Enable' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Users with access_id = 1 -->
        <h2 class="font-bold text-xl mb-4 mt-8">Request</h2>
        <div class="space-y-4">
            @foreach($requests as $user)
                <div class="flex items-center justify-between p-4 border rounded-lg">
                    <div class="flex items-center space-x-4">
                        <div>
                            <div class="font-semibold">{{ $user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <!-- Disable Button -->
                        <form action="{{ route('admin.toggle-user', $user->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="disable" value="1"> <!-- Hidden input for disabling -->
                            <button type="submit" class="px-4 py-2 rounded bg-red-500 hover:bg-red-600 text-black">
                                &#10006; <!-- X -->
                            </button>
                        </form>
                        <!-- Enable Button -->
                        <form action="{{ route('admin.toggle-user', $user->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="enable" value="1"> <!-- Hidden input for enabling -->
                            <button type="submit" class="px-4 py-2 rounded bg-green-500 hover:bg-green-600 text-black">
                                &#10004; <!-- Check -->
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection