@extends('layouts.app')
@section('title', 'Services')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-sm text-gray-400">{{ $services->count() }} services configured</p>
        </div>
        <a href="{{ route('admin.services.create') }}"
            class="inline-flex items-center gap-1.5 bg-violet-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-violet-700 transition">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
            </svg>
            Add Service
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($services as $service)
            <div
                class="bg-white border border-gray-100 rounded-2xl p-6 flex flex-col gap-4 shadow-sm hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-display font-bold text-gray-900">{{ $service->name }}</h3>
                        <p class="text-gray-400 text-sm mt-1 leading-relaxed line-clamp-2">
                            {{ $service->description ?? 'No description.' }}</p>
                    </div>
                    <span class="ml-2 px-2.5 py-1 rounded-full text-[11px] font-semibold flex-shrink-0
                                {{ $service->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-400' }}">
                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="bg-gray-50 border border-gray-100 text-gray-500 text-xs font-medium px-2.5 py-1 rounded-lg">⏱
                        {{ $service->duration_minutes }} min</span>
                    @if($service->price)
                        <span
                            class="bg-gray-50 border border-gray-100 text-gray-500 text-xs font-medium px-2.5 py-1 rounded-lg">${{ number_format($service->price, 2) }}</span>
                    @endif
                </div>

                <div class="flex gap-2 pt-2 border-t border-gray-50">
                    <a href="{{ route('admin.services.edit', $service) }}"
                        class="flex-1 text-center text-xs font-semibold text-violet-600 border border-violet-100 bg-violet-50/50 rounded-lg py-2 hover:bg-violet-50 transition">Edit</a>
                    <form method="POST" action="{{ route('admin.services.toggle', $service) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="text-xs font-semibold text-amber-600 border border-amber-100 bg-amber-50/50 rounded-lg py-2 px-3 hover:bg-amber-50 transition">
                            {{ $service->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.services.destroy', $service) }}"
                        onsubmit="return confirm('Delete {{ $service->name }}?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="text-xs font-semibold text-red-500 border border-red-100 bg-red-50/50 rounded-lg py-2 px-3 hover:bg-red-50 transition">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-white border border-gray-100 rounded-2xl py-20 text-center text-gray-400 text-sm">
                No services yet. <a href="{{ route('admin.services.create') }}"
                    class="text-violet-600 font-medium hover:underline">Add your first service →</a>
            </div>
        @endforelse
    </div>
@endsection