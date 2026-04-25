@extends('layouts.app')
@section('title', 'Edit Service')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Service</h1>
        <p class="text-gray-500 text-sm mt-1">{{ $service->name }}</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <form method="POST" action="{{ route('admin.services.update', $service) }}" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Service Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $service->name) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $service->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Duration (min) <span class="text-red-500">*</span></label>
                    <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $service->duration_minutes) }}" min="1" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price ($)</label>
                    <input type="number" name="price" value="{{ old('price', $service->price) }}" step="0.01" min="0" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
                <label for="is_active" class="text-sm text-gray-700">Active</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-xl font-semibold text-sm hover:bg-blue-700 transition">Save Changes</button>
                <a href="{{ route('admin.services.index') }}" class="px-5 py-3 border border-gray-200 text-gray-600 rounded-xl text-sm hover:bg-gray-50">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection