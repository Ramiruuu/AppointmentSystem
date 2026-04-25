@extends('layouts.app')
@section('title', 'Profile')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>

    <!-- Update Profile -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <h2 class="text-base font-semibold text-gray-900 mb-5">Profile Information</h2>

        @if(session('status') === 'profile-updated')
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-2.5 text-sm">Profile updated successfully.</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf @method('PATCH')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold text-sm hover:bg-blue-700 transition">Save Changes</button>
        </form>
    </div>

    <!-- Delete Account -->
    <div class="bg-white rounded-2xl border border-red-100 shadow-sm p-8" x-data="{ open: false }">
        <h2 class="text-base font-semibold text-red-700 mb-2">Delete Account</h2>
        <p class="text-sm text-gray-500 mb-4">Once deleted, all your data will be permanently removed.</p>
        <button @click="open = true" class="text-sm text-red-600 border border-red-200 rounded-xl px-4 py-2 hover:bg-red-50 transition">Delete My Account</button>

        <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div @click.outside="open = false" class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Are you sure?</h3>
                <p class="text-sm text-gray-500 mb-4">Enter your password to confirm.</p>
                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf @method('DELETE')
                    <input type="password" name="password" required placeholder="Your password"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm mb-4 focus:outline-none focus:ring-2 focus:ring-red-400">
                    @error('password', 'userDeletion')<p class="text-red-500 text-xs -mt-3 mb-3">{{ $message }}</p>@enderror
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 bg-red-600 text-white py-2.5 rounded-xl text-sm font-medium hover:bg-red-700">Delete</button>
                        <button type="button" @click="open = false" class="px-4 border border-gray-200 rounded-xl text-sm text-gray-600 hover:bg-gray-50">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection