@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
@php
    $user = Auth::user();
    $total     = $user->appointments()->count();
    $upcoming  = $user->appointments()->upcoming()->whereNotIn('status', ['cancelled'])->count();
    $completed = $user->appointments()->where('status', 'completed')->count();
    $cancelled = $user->appointments()->where('status', 'cancelled')->count();
    $upcomingList = $user->appointments()->with('service')->upcoming()->whereNotIn('status',['cancelled'])->orderBy('appointment_date')->limit(5)->get();
@endphp

<div class="space-y-6">

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label'=>'Total','value'=>$total,'chip'=>'All time','chipClass'=>'bg-violet-50 text-violet-700'],
            ['label'=>'Upcoming','value'=>$upcoming,'chip'=>'Scheduled','chipClass'=>'bg-blue-50 text-blue-700'],
            ['label'=>'Completed','value'=>$completed,'chip'=>'Done','chipClass'=>'bg-emerald-50 text-emerald-700'],
            ['label'=>'Cancelled','value'=>$cancelled,'chip'=>'Void','chipClass'=>'bg-red-50 text-red-700'],
        ] as $s)
        <div class="bg-white border border-gray-100 rounded-2xl p-5">
            <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-3">{{ $s['label'] }}</div>
            <div class="font-display font-bold text-4xl text-gray-900 mb-3">{{ $s['value'] }}</div>
            <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full {{ $s['chipClass'] }}">{{ $s['chip'] }}</span>
        </div>
        @endforeach
    </div>

    @if($user->isAdmin())
    @php $pending = \App\Models\Appointment::where('status','pending')->count(); $clients = \App\Models\User::where('role','client')->count(); @endphp
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="text-[11px] font-semibold text-amber-600 uppercase tracking-wider">Pending Approvals</div>
                <div class="font-display font-bold text-2xl text-amber-800">{{ $pending }}</div>
            </div>
        </div>
        <div class="bg-violet-50 border border-violet-100 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <div class="text-[11px] font-semibold text-violet-600 uppercase tracking-wider">Total Clients</div>
                <div class="font-display font-bold text-2xl text-violet-800">{{ $clients }}</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Upcoming -->
    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
            <div class="font-display font-bold text-gray-900">Upcoming Appointments</div>
            <a href="{{ route('appointments.index') }}" class="text-violet-600 text-sm font-medium hover:text-violet-800 transition">View all →</a>
        </div>

        @if($upcomingList->isEmpty())
        <div class="py-16 text-center">
            <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <p class="text-gray-400 text-sm">No upcoming appointments</p>
            <a href="{{ route('appointments.create') }}" class="mt-2 inline-block text-violet-600 text-sm font-medium hover:underline">Book one now →</a>
        </div>
        @else
        @php $statusColors = ['pending'=>'amber','confirmed'=>'emerald','completed'=>'blue','cancelled'=>'red']; @endphp
        <div class="divide-y divide-gray-50">
            @foreach($upcomingList as $apt)
            @php $c = $statusColors[$apt->status] ?? 'gray'; @endphp
            <div class="px-6 py-4 flex items-center justify-between group hover:bg-gray-50/50 transition">
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 rounded-xl bg-violet-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 text-sm">{{ $apt->service->name }}</p>
                        <p class="text-gray-400 text-xs mt-0.5">{{ $apt->appointment_date->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-{{ $c }}-50 text-{{ $c }}-700">{{ ucfirst($apt->status) }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection