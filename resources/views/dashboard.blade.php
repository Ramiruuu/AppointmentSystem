@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

@php
    $slotLabels = [
        'morning_1'   => 'Morning · 7:00 AM – 9:00 AM',
        'morning_2'   => 'Late Morning · 10:00 AM – 12:00 PM',
        'afternoon_1' => 'Afternoon · 1:00 PM – 3:00 PM',
        'afternoon_2' => 'Late Afternoon · 4:00 PM – 6:00 PM',
    ];
@endphp

<div class="space-y-7">

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="flex items-start justify-between gap-4 bg-green-50 border-l-4 border-green-500 rounded-lg px-5 py-4">
            <div>
                <p class="text-sm font-semibold text-green-800">Appointment booked successfully!</p>
                <p class="text-sm text-green-700 mt-0.5">{{ session('success') }}</p>
            </div>
            <button onclick="this.closest('div.flex').remove()" class="text-green-500 hover:text-green-700 text-lg leading-none">&times;</button>
        </div>
    @endif

    {{-- ERROR --}}
    @if(session('error'))
        <div class="flex items-start justify-between gap-4 bg-red-50 border-l-4 border-red-500 rounded-lg px-5 py-4">
            <p class="text-sm text-red-700">{{ session('error') }}</p>
            <button onclick="this.closest('div.flex').remove()" class="text-red-400 hover:text-red-600 text-lg leading-none">&times;</button>
        </div>
    @endif

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">Good day, {{ Auth::user()->name }}</h1>
            <p class="text-sm text-gray-400 mt-0.5">Here's your appointment overview</p>
        </div>
        <a href="{{ route('appointments.create') }}"
            class="px-4 py-2 bg-indigo-900 text-indigo-50 text-sm font-medium rounded-lg hover:bg-indigo-800 transition">
            + Book Appointment
        </a>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 px-5 py-4">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total</p>
            <p class="text-3xl font-semibold text-gray-900 mt-2">{{ $totalAppointments }}</p>
        </div>
        <div class="bg-indigo-50 rounded-xl px-5 py-4">
            <p class="text-xs font-medium text-indigo-500 uppercase tracking-wider">Upcoming</p>
            <p class="text-3xl font-semibold text-indigo-900 mt-2">{{ $upcomingAppointments }}</p>
        </div>
        <div class="bg-green-50 rounded-xl px-5 py-4">
            <p class="text-xs font-medium text-green-600 uppercase tracking-wider">Completed</p>
            <p class="text-3xl font-semibold text-green-900 mt-2">{{ $completedAppointments }}</p>
        </div>
        <div class="bg-red-50 rounded-xl px-5 py-4">
            <p class="text-xs font-medium text-red-500 uppercase tracking-wider">Cancelled</p>
            <p class="text-3xl font-semibold text-red-900 mt-2">{{ $cancelledAppointments }}</p>
        </div>
    </div>

    {{-- ADMIN CHARTS --}}
    @if(Auth::user()->isAdmin())
        @php
            $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            $yearTotal = \App\Models\Appointment::whereYear('appointment_date', now()->year)->count();
            $allTotal  = \App\Models\Appointment::count();
            $statusRows = [
                'pending'   => ['label' => 'Pending',   'color' => 'bg-amber-400'],
                'confirmed' => ['label' => 'Confirmed', 'color' => 'bg-blue-500'],
                'completed' => ['label' => 'Completed', 'color' => 'bg-green-500'],
                'cancelled' => ['label' => 'Cancelled', 'color' => 'bg-red-400'],
            ];
        @endphp
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
                <p class="text-sm font-medium text-gray-700 mb-4">Monthly appointments ({{ now()->year }})</p>
                <div class="space-y-3">
                    @foreach(array_slice($months, 0, 6) as $i => $month)
                        @php
                            $cnt = \App\Models\Appointment::whereMonth('appointment_date', $i + 1)->whereYear('appointment_date', now()->year)->count();
                            $pct = $yearTotal > 0 ? round(($cnt / $yearTotal) * 100) : 0;
                        @endphp
                        <div class="flex items-center gap-3 text-sm">
                            <span class="text-gray-400 w-8">{{ $month }}</span>
                            <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gray-800 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="text-gray-700 font-medium w-5 text-right">{{ $cnt }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl px-6 py-5">
                <p class="text-sm font-medium text-gray-700 mb-4">Appointment status</p>
                <div class="space-y-4">
                    @foreach($statusRows as $status => $info)
                        @php
                            $cnt = \App\Models\Appointment::where('status', $status)->count();
                            $pct = $allTotal > 0 ? round(($cnt / $allTotal) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1.5">
                                <span class="text-gray-500">{{ $info['label'] }}</span>
                                <span class="text-gray-800 font-medium">{{ $cnt }}</span>
                            </div>
                            <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full {{ $info['color'] }} rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- UPCOMING BOOKINGS TABLE --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <p class="text-sm font-medium text-gray-900">Upcoming bookings</p>
            <a href="{{ route('appointments.index') }}" class="text-xs text-indigo-600 hover:underline">View all</a>
        </div>

        @if($upcomingList->isEmpty())
            <div class="py-16 text-center">
                <p class="text-sm text-gray-400">No upcoming bookings yet.</p>
                <a href="{{ route('appointments.create') }}" class="mt-2 inline-block text-sm text-indigo-600 hover:underline">Book an appointment</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm" style="table-layout: fixed;">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-[18%]">Service</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-[13%]">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-[22%]">Time Slot</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-[16%]">Location</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-[14%]">Queue No.</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-[10%]">Payment</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-[7%]">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($upcomingList as $apt)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-4">
                                    <p class="font-medium text-gray-900 truncate">{{ $apt->service->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $apt->service->duration_minutes ?? '-' }} min</p>
                                </td>
                                <td class="px-4 py-4 text-gray-500 text-xs">
                                    {{ $apt->appointment_date->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-4 text-gray-500 text-xs">
                                    {{ $slotLabels[$apt->time_slot] ?? ucwords(str_replace('_', ' ', $apt->time_slot)) }}
                                </td>
                                <td class="px-4 py-4 text-xs">
                                    <p class="text-gray-700 font-medium truncate">{{ $apt->hospital->name ?? $apt->preferred_location ?? 'N/A' }}</p>
                                    @if($apt->hospital->address ?? false)
                                        <p class="text-gray-400 mt-0.5 truncate">{{ $apt->hospital->address }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-xs">
                                    <p class="font-medium text-gray-800">{{ $apt->queue_number ?? 'N/A' }}</p>
                                    @if($apt->slot_position)
                                        <p class="text-gray-400 mt-0.5">Position {{ $apt->slot_position }} of 10</p>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <p class="text-xs text-gray-600">{{ ucfirst(str_replace('_', ' ', $apt->payment_method ?? '—')) }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-medium
                                        @if($apt->payment_status === 'paid') bg-green-100 text-green-800
                                        @elseif($apt->payment_status === 'partial') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-500 @endif">
                                        {{ ucfirst($apt->payment_status ?? 'unpaid') }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium
                                        @if($apt->status === 'confirmed') bg-blue-100 text-blue-800
                                        @elseif($apt->status === 'pending') bg-amber-100 text-amber-800
                                        @elseif($apt->status === 'completed') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ ucfirst($apt->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- ADMIN: GENERATE REPORT --}}
    @if(Auth::user()->isAdmin())
        <div class="flex justify-end">
            <button onclick="window.print()"
                class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                Generate Report
            </button>
        </div>
    @endif

</div>
@endsection