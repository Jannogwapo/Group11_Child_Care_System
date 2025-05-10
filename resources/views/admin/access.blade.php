@extends('layout')
@section('title', 'Access Management')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">User Access Management</h1>
    </div>

    <!-- Admin Users Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="font-bold text-xl mb-4 mt-8">ADMIN USERS</h2>
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b text-left">Name</th>
                    <th class="py-2 px-4 border-b text-left">Status</th>
                    <th class="py-2 px-4 border-b text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usersByRole['admin'] ?? [] as $user)
                    <tr>
                        <td class="py-2 px-4 border-b">
                            <div class="font-semibold">{{ $user->name }}</div>
                        </td>
                        <td class="py-2 px-4 border-b">
                            <span class="{{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                                ● {{ $user->is_active ? 'Disabled' : 'Active' }}
                            </span>
                        </td>
                        <td class="py-2 px-4 border-b">
                            @if(auth()->user()->id !== $user->id)
                                <form action="{{ route('admin.toggle-user', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="access_id" value="3"> <!-- Set access_id to 3 -->
                                    <button type="submit" class="px-3 py-1 rounded bg-red-500 hover:bg-red-600 text-white">
                                        Disable
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Social Worker Users Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="font-bold text-xl mb-4 mt-8">SOCIAL WORKER USERS</h2>
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b text-left">Name</th>
                    <th class="py-2 px-4 border-b text-left">Status</th>
                    <th class="py-2 px-4 border-b text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usersByRole['social_worker'] ?? [] as $user)
                    <tr>
                        <td class="py-2 px-4 border-b">
                            <div class="font-semibold">{{ $user->name }}</div>
                        </td>
                        <td class="py-2 px-4 border-b">
                            <span class="{{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                                ● {{ $user->is_active ? 'Disabled' : 'Active' }}
                            </span>
                        </td>
                        <td class="py-2 px-4 border-b">
                            @if(auth()->user()->id !== $user->id)
                                <form action="{{ route('admin.toggle-user', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="access_id" value="3"> <!-- Set access_id to 3 -->
                                    <button type="submit" class="px-3 py-1 rounded bg-red-500 hover:bg-red-600 text-white">
                                        Disable
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Disabled Users Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="font-bold text-xl mb-4 mt-8">DISABLED USERS</h2>
        @if($disabledUsers->isEmpty())
            <p class="text-gray-500">No disabled users found.</p>
        @else
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b text-left">Name</th>
                        <th class="py-2 px-4 border-b text-left">Email</th>
                        <th class="py-2 px-4 border-b text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($disabledUsers as $user)
                        <tr>
                            <!-- Name -->
                            <td class="py-2 px-4 border-b">
                                <div class="font-semibold">{{ $user->name }}</div>
                            </td>

                            <!-- Email -->
                            <td class="py-2 px-4 border-b">
                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                            </td>

                            <!-- Action -->
                            <td class="py-2 px-4 border-b">
                                <form action="{{ route('admin.toggle-user', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="access_id" value="2"> <!-- Set access_id to 2 -->
                                    <button type="submit" class="px-4 py-2 rounded bg-green-500 hover:bg-green-600 text-white">
                                        Enable
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Request Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="font-bold text-xl mb-4 mt-8">REQUEST</h2>
        @if($requests->isEmpty())
            <p class="text-gray-500">No requests found.</p>
        @else
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b text-left">Name</th>
                        <th class="py-2 px-4 border-b text-left">Email</th>
                        <th class="py-2 px-4 border-b text-left">Status</th>
                        <th class="py-2 px-4 border-b text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $user)
                        <tr>
                            <!-- Name -->
                            <td class="py-2 px-4 border-b">
                                <div class="font-semibold">{{ $user->name }}</div>
                            </td>

                            <!-- Email -->
                            <td class="py-2 px-4 border-b">
                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                            </td>

                            <!-- Status -->
                            <td class="py-2 px-4 border-b">
                                <span class="text-yellow-600 font-semibold">Pending</span>
                            </td>

                            <!-- Action -->
                            <td class="py-2 px-4 border-b">
                                <div class="flex space-x-2">
                                    <!-- Approve Button -->
                                    <form action="{{ route('admin.toggle-user', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="access_id" value="2"> <!-- Approve -->
                                        <button type="submit" class="px-4 py-2 rounded bg-green-500 hover:bg-green-600 text-white">
                                            Approve
                                        </button>
                                    </form>

                                    <!-- Disapprove Button -->
                                    <form action="{{ route('admin.toggle-user', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="access_id" value="3"> <!-- Disapprove -->
                                        <button type="submit" class="px-4 py-2 rounded bg-red-500 hover:bg-red-600 text-white">
                                            Disapprove
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection