@extends('layout')
@section('title', 'Access Management')
@section('content')

<!-- Inline CSS for Access Management Page (with padding adjustments and readable font size) -->
<style>
body {
    background: #f3f4f6;
    font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
    color: #222;
    font-size: 1.1rem;
}
.container {
    max-width: 1000px;
    margin: 2rem auto;
    background: #f9fafb;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(30,41,59,0.06), 0 1.5px 10px rgba(30,41,59,0.06);
    padding: 2.5rem 2rem 2.5rem 2rem; /* More padding for outer box */
}
.bg-white {
    background-color: #fff;
    border-radius: 1rem;
    box-shadow: 0 4px 20px rgba(30,41,59,0.08);
    padding: 2rem 1.5rem 2rem 1.5rem; /* More padding for inner box */
    margin-bottom: 2rem;
}
h1, h2 {
    color: #1e293b;
}
h1 {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}
h2 {
    font-size: 1.35rem;
    margin-top: 2rem;
    margin-bottom: 1.2rem;
    font-weight: 700;
}
table {
    width: 100%;
    border-spacing: 0;
    border-collapse: collapse;
    margin-bottom: 1.5rem;
    font-size: 1rem;
}
th, td {
    padding: 0.85rem 1.2rem;
    text-align: left;
}
th {
    background: #f1f5f9;
    font-size: 1.05rem;
    font-weight: 600;
    border-bottom: 2px solid #e5e7eb;
}
td {
    background: #fff;
    border-bottom: 1px solid #e5e7eb;
    font-size: 1.05rem;
}
tr:last-child td {
    border-bottom: none;
}
form.inline-block {
    display: inline;
}
button,
input[type="submit"] {
    transition: background 0.2s, color 0.2s;
    border: none;
    font-weight: 600;
    cursor: pointer;
    outline: none;
    font-size: 1rem;
}
.bg-red-500 {
    background-color: #ef4444;
}
.bg-red-600 {
    background-color: #dc2626;
}
.bg-green-500 {
    background-color: #22c55e;
}
.bg-green-600 {
    background-color: #16a34a;
}
.text-black {
    color: #222 !important;
}
.text-gray-500 {
    color: #6b7280 !important;
}
.text-gray-800 {
    color: #1e293b !important;
}
.text-green-600 {
    color: #16a34a !important;
}
.text-red-600 {
    color: #dc2626 !important;
}
.text-yellow-600 {
    color: #ca8a04 !important;
}
.font-semibold {
    font-weight: 600;
}
.font-bold {
    font-weight: 700;
}
.rounded {
    border-radius: 0.375rem;
}
.rounded-lg {
    border-radius: 1rem;
}
.shadow-lg {
    box-shadow: 0 4px 20px rgba(30,41,59,0.08);
}
.px-3 {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
}
.px-4 {
    padding-left: 1rem;
    padding-right: 1rem;
}
.py-1 {
    padding-top: 0.25rem;
    padding-bottom: 0.25rem;
}
.py-2 {
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
}
.p-6 {
    padding: 1.5rem;
}
.mb-4 {
    margin-bottom: 1rem;
}
.mb-6 {
    margin-bottom: 1.5rem;
}
.mt-8 {
    margin-top: 2rem;
}
.text-xl {
    font-size: 1.25rem;
}
.text-2xl {
    font-size: 1.5rem;
}
.flex {
    display: flex;
}
.justify-between {
    justify-content: space-between;
}
.items-center {
    align-items: center;
}
.hover\:bg-red-600:hover {
    background-color: #dc2626 !important;
}
.hover\:bg-green-600:hover {
    background-color: #16a34a !important;
}
</style>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">User Access Management</h1>
    </div>

    <!-- Admin Users Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="font-bold text-xl mb-4 mt-8">ADMIN USERS</h2>
        @if($usersByRole['admin']->isEmpty())
            <p class="text-gray-500">No admin users found.</p>
        @else
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b text-left">Name</th>
                    <th class="py-2 px-4 border-b text-left">Status</th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach($usersByRole['admin'] ?? [] as $user)
                    <tr>
                        <td class="py-2 px-4 border-b">
                            <div class="font-semibold">{{ $user->name }}</div>
                        </td>
                        <td class="py-2 px-4 border-b">
                            <span class="{{ $user->is_active ? 'text-green-600' : 'text-green-600' }}">
                                ● {{ $user->is_active ? 'Disabled' : 'Active' }}
                            </span>
                        </td>
                        <td class="py-2 px-4 border-b">
                            @if(auth()->user()->id !== $user->id)
                                <form action="{{ route('admin.toggle-user', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="access_id" value="3"> <!-- Set access_id to 3 -->
                                    <button type="submit" class="px-3 py-1 rounded bg-red-500 hover:bg-red-600 text-black">
                                        Disable
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <!-- Social Worker Users Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="font-bold text-xl mb-4 mt-8">SOCIAL WORKER USERS</h2>
        @if($usersByRole['social_worker']->isEmpty())
            <p class="text-gray-500">No social worker users found.</p>
        @else
        <table class="min-w-full bg-white ">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b text-left">Name</th>
                    <th class="py-2 px-4 border-b text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usersByRole['social_worker'] ?? [] as $user)
                    <tr>
                        <td class="py-2 px-4 border-b">
                            <div class="font-semibold">{{ $user->name }}</div>
                        </td>
                        <td class="py-2 px-4 border-b">
                            <span class="{{ $user->is_active ? 'text-green-600' : 'text-green-600' }}">
                                ● {{ $user->is_active ? 'Disabled' : 'Active' }}
                            </span>
                        </td>
                        <td class="py-2 px-4 border-b">
                            @if(auth()->user()->id !== $user->id)
                                <form action="{{ route('admin.toggle-user', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="access_id" value="3"> <!-- Set access_id to 3 -->
                                    <button type="submit" class="px-3 py-1 rounded bg-red-500 hover:bg-red-600 text-black">
                                        Disable
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <!-- Disabled Users Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="font-bold text-xl mb-4 mt-8">DISABLED USERS</h2>
        @if($disabledUsers->isEmpty())
            <p class="text-gray-500">No disabled users found.</p>
        @else
            <table class="min-w-full bg-white ">
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
                                    <button type="submit" class="px-4 py-2 rounded bg-green-500 hover:bg-green-600 text-black">
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
            <table class="min-w-full bg-white ">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b text-left">Name</th>
                        <th class="py-2 px-4 border-b text-left">Email</th>
                        <th class="py-2 px-4 border-b text-left">Status</th>
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
                                
                                    <!-- Approve Button -->
                                    <form action="{{ route('admin.toggle-user', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="access_id" value="2"> <!-- Approve -->
                                        <button type="submit" class="px-4 py-2 rounded bg-green-500 hover:bg-green-600 text-black">
                                            Approve
                                        </button>
                                    </form>
                            </td>
                            <td>
                                    <!-- Disapprove Button -->
                                    <form action="{{ route('admin.toggle-user', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="access_id" value="3"> <!-- Disapprove -->
                                        <button type="submit" class="px-4 py-2 rounded bg-red-500 hover:bg-red-600 text-black">
                                            Disapprove
                                        </button>
                                    </form>
                               
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection@endsection
