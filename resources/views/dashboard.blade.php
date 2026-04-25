@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
@php
    $user = Auth::user();
    $totalAppointments = $user->appointments()->count();
    $upcomingAppointments = $user->appointments()->upcoming()->whereNotIn('status', ['cancelled'])->count();
    $completedAppointments = $user->appointments()->where('status', 'completed')->count();
    $cancelledAppointments = $user->appointments()->where('status', 'cancelled')->count();
    $upcomingList = $user->appointments()->with('service')->upcoming()->whereNotIn('status',['cancelled'])->orderBy('appointment_date')->limit(5)->get();
    
    // Calculate percentages
    $upcomingPercentage = $totalAppointments > 0 ? round(($upcomingAppointments / $totalAppointments) * 100) : 0;
    $cancelledPercentage = $totalAppointments > 0 ? round(($cancelledAppointments / $totalAppointments) * 100) : 0;
    $completedPercentage = $totalAppointments > 0 ? round(($completedAppointments / $totalAppointments) * 100) : 0;
@endphp

<div class="space-y-8">

    <!-- Welcome Header -->
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Welcome back, {{ $user->name }}</h1>
        <p class="text-sm text-gray-500 mt-1">Here's what's happening with your appointments</p>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Appointments</p>
                    <p class="text-3xl font-semibold text-gray-900 mt-2">{{ $totalAppointments }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-emerald-600 font-medium">{{ $completedPercentage }}%</p>
                    <p class="text-xs text-gray-400 mt-1">completed</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Upcoming</p>
                    <p class="text-3xl font-semibold text-gray-900 mt-2">{{ $upcomingAppointments }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-blue-600 font-medium">{{ $upcomingPercentage }}%</p>
                    <p class="text-xs text-gray-400 mt-1">of total</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Completed</p>
                    <p class="text-3xl font-semibold text-gray-900 mt-2">{{ $completedAppointments }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-emerald-600 font-medium">{{ $completedPercentage }}%</p>
                    <p class="text-xs text-gray-400 mt-1">done</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Cancelled</p>
                    <p class="text-3xl font-semibold text-gray-900 mt-2">{{ $cancelledAppointments }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-rose-600 font-medium">{{ $cancelledPercentage }}%</p>
                    <p class="text-xs text-gray-400 mt-1">cancelled</p>
                </div>
            </div>
        </div>
    </div>

    @if($user->isAdmin())
    <!-- Monthly Overview & Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Appointments -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Monthly Appointments</h3>
            <div class="space-y-3">
                @php
                    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                @endphp
                @foreach(array_slice($months, 0, 6) as $index => $month)
                    @php
                        $monthNumber = $index + 1;
                        $count = \App\Models\Appointment::whereMonth('appointment_date', $monthNumber)
                            ->whereYear('appointment_date', now()->year)
                            ->count();
                        $maxCount = \App\Models\Appointment::whereYear('appointment_date', now()->year)->count();
                        $percentage = $maxCount > 0 ? round(($count / $maxCount) * 100) : 0;
                    @endphp
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500 w-12">{{ $month }}</span>
                        <div class="flex-1 mx-4">
                            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gray-800 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                        <span class="text-gray-900 font-medium w-8 text-right">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Appointment Status Breakdown -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Appointment Status</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-500">Pending</span>
                        <span class="text-gray-900 font-medium">{{ \App\Models\Appointment::where('status', 'pending')->count() }}</span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-500 rounded-full" style="width: {{ $totalAppointments > 0 ? round((\App\Models\Appointment::where('status', 'pending')->count() / $totalAppointments) * 100) : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-500">Confirmed</span>
                        <span class="text-gray-900 font-medium">{{ \App\Models\Appointment::where('status', 'confirmed')->count() }}</span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-600 rounded-full" style="width: {{ $totalAppointments > 0 ? round((\App\Models\Appointment::where('status', 'confirmed')->count() / $totalAppointments) * 100) : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-500">Completed</span>
                        <span class="text-gray-900 font-medium">{{ $completedAppointments }}</span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-600 rounded-full" style="width: {{ $completedPercentage }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-500">Cancelled</span>
                        <span class="text-gray-900 font-medium">{{ $cancelledAppointments }}</span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-rose-600 rounded-full" style="width: {{ $cancelledPercentage }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Upcoming Bookings Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-900">Upcoming Bookings</h3>
        </div>
        
        @if($upcomingList->isEmpty())
        <div class="py-12 text-center">
            <p class="text-gray-400 text-sm">No upcoming bookings</p>
            <a href="{{ route('appointments.create') }}" class="mt-2 inline-block text-sm text-gray-500 hover:text-gray-700">Book an appointment →</a>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-left">
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                    <tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($upcomingList as $apt)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $apt->service->name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $apt->appointment_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $apt->appointment_date->format('g:i A') }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $apt->service->duration_minutes }} min</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                @if($apt->status == 'pending') bg-amber-50 text-amber-700
                                @elseif($apt->status == 'confirmed') bg-blue-50 text-blue-700
                                @elseif($apt->status == 'completed') bg-emerald-50 text-emerald-700
                                @else bg-rose-50 text-rose-700 @endif">
                                {{ ucfirst($apt->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('appointments.index') }}" class="text-gray-400 hover:text-gray-600 transition">→</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end gap-3">
        <a href="{{ route('appointments.create') }}" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition">
            Book Appointment
        </a>
        @if($user->isAdmin())
        <button onclick="window.print()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            Generate Report
        </button>
        @endif
    </div>
</div>
@endsection