@extends('layouts.app')
@section('title', 'Cancel Appointment')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white border border-gray-200 rounded-2xl p-6">
        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-900 mb-2">Cancel Appointment</h2>
        <p class="text-gray-500 text-sm mb-4">Are you sure you want to cancel your {{ $appointment->service->name }} appointment on {{ $appointment->appointment_date->format('M d, Y g:i A') }}?</p>
        
        <form method="POST" action="{{ route('appointments.cancel', $appointment) }}">
            @csrf
            @method('DELETE')
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for cancellation</label>
                <textarea name="cancellation_reason" rows="3" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500"
                    placeholder="Please tell us why you're cancelling..."></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-red-500 text-white py-3 rounded-xl font-semibold hover:bg-red-600 transition">
                    Confirm Cancel
                </button>
                <a href="{{ route('appointments.index') }}" class="flex-1 text-center px-4 py-3 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition">
                    Go Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection