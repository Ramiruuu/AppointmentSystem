@extends('layouts.app')
@section('title', 'All Appointments')

@section('content')
@php $statusColors = ['pending'=>'amber','confirmed'=>'emerald','completed'=>'blue','cancelled'=>'red']; @endphp

<div x-data="{ statusModal: false, currentId: null, currentStatus: '' }">

    <!-- Filters -->
    <form method="GET" class="bg-white border border-gray-100 rounded-2xl p-5 mb-6 shadow-sm">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            <div>
                <label class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Status</label>
                <select name="status" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 bg-white">
                    <option value="">All Statuses</option>
                    @foreach(['pending','confirmed','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-1.5">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email..."
                    class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-violet-600 text-white rounded-xl px-4 py-2.5 text-sm font-semibold hover:bg-violet-700 transition">Filter</button>
                <a href="{{ route('admin.appointments.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-500 hover:bg-gray-50 transition font-medium">Reset</a>
            </div>
        </div>
    </form>

    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Service</th>
                    <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Date & Time</th>
                    <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($appointments as $apt)
                @php $c = $statusColors[$apt->status] ?? 'gray'; @endphp
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-violet-50 flex items-center justify-center text-violet-600 text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($apt->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $apt->user->name }}</p>
                                <p class="text-gray-400 text-xs">{{ $apt->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $apt->service->name }}</td>
                    <td class="px-6 py-4 text-gray-400">{{ $apt->appointment_date->format('M d, Y · g:i A') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-{{ $c }}-50 text-{{ $c }}-700">{{ ucfirst($apt->status) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <button @click="statusModal=true; currentId={{ $apt->id }}; currentStatus='{{ $apt->status }}'"
                            class="text-violet-600 text-sm font-semibold hover:text-violet-800 transition">Update →</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center text-gray-400 text-sm">No appointments found matching your filters.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $appointments->links() }}</div>

    <!-- Status Modal -->
    <div x-show="statusModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div @click.outside="statusModal=false" class="bg-white rounded-2xl shadow-2xl w-full max-w-xs p-6">
            <h3 class="font-display font-bold text-gray-900 mb-4">Update Appointment Status</h3>
            <form method="POST" :action="`/admin/appointments/${currentId}/status`">
                @csrf @method('PATCH')
                <select name="status" x-model="currentStatus"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm mb-4 focus:outline-none focus:ring-2 focus:ring-violet-500">
                    @foreach(['pending','confirmed','completed','cancelled'] as $s)
                    <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-violet-600 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-violet-700 transition">Save</button>
                    <button type="button" @click="statusModal=false" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-500 hover:bg-gray-50 transition">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection