@extends('layouts.app')
@section('title', 'My Appointments')

@section('content')
<!-- Flash Messages with new styling -->
@if(session('success'))
<div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg shadow-sm">
    <div class="flex items-center">
        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <p class="ml-3 text-sm text-green-700">{{ session('success') }}</p>
    </div>
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-sm">
    <div class="flex items-center">
        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <p class="ml-3 text-sm text-red-700">{{ session('error') }}</p>
    </div>
</div>
@endif

@php $statusColors = ['pending' => 'amber', 'confirmed' => 'emerald', 'completed' => 'blue', 'cancelled' => 'red']; @endphp

<div x-data="{ 
    tab: 'upcoming', 
    cancelModal: false, 
    cancelId: null,
    paymentModal: false,
    selectedAppointment: null
}">

    <div class="flex gap-1.5 bg-white border border-gray-100 rounded-xl p-1 w-fit mb-6 shadow-sm">
        <button @click="tab='upcoming'"
            :class="tab==='upcoming' ? 'bg-violet-600 text-white shadow-sm' : 'text-gray-500 hover:text-gray-800'"
            class="px-5 py-2 rounded-lg text-sm font-medium transition-all">
            Upcoming ({{ $upcoming->count() }})
        </button>
        <button @click="tab='past'"
            :class="tab==='past' ? 'bg-violet-600 text-white shadow-sm' : 'text-gray-500 hover:text-gray-800'"
            class="px-5 py-2 rounded-lg text-sm font-medium transition-all">
            Past ({{ $past->count() }})
        </button>
    </div>

    <!-- Upcoming -->
    <div x-show="tab==='upcoming'">
        @if($upcoming->isEmpty())
            <div class="bg-white border border-gray-100 rounded-2xl py-20 text-center">
                <div class="w-14 h-14 bg-violet-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <p class="text-gray-400 text-sm">No upcoming appointments</p>
                <a href="{{ route('appointments.create') }}"
                    class="mt-2 inline-block text-violet-600 text-sm font-medium hover:underline">Book one →</a>
            </div>
        @else
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th
                                class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                                Service</th>
                            <th
                                class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                                Date & Time</th>
                            <th
                                class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                                Location</th>
                            <th
                                class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                                Price</th>
                            <th
                                class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                                Payment</th>
                            <th
                                class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                                Action</th>
                         </>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($upcoming as $apt)
                            @php $c = $statusColors[$apt->status] ?? 'gray'; @endphp
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $apt->service->name }}</td>
                                <td class="px-6 py-4 text-gray-400">{{ $apt->appointment_date->format('M d, Y · g:i A') }}</td>
                                <td class="px-6 py-4 text-gray-400">
                                    {{ $apt->preferred_location ?? ($apt->location_address ? substr($apt->location_address, 0, 40) : 'Not specified') }}
                                </td>
                                <td class="px-6 py-4 text-gray-500 font-medium">₱{{ number_format($apt->service->price, 2) }}</td>
                                <td class="px-6 py-4">
                                    @if($apt->payment_status == 'paid')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-emerald-50 text-emerald-700">
                                            Paid
                                        </span>
                                    @elseif($apt->payment_status == 'partial')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-amber-50 text-amber-700">
                                            Partial (₱{{ number_format($apt->amount_paid, 2) }})
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-50 text-red-700">
                                            Unpaid
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs font-semibold bg-{{ $c }}-50 text-{{ $c }}-700">{{ ucfirst($apt->status) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        @if(in_array($apt->status, ['pending', 'confirmed']) && $apt->appointment_date->isFuture())
                                            <button @click="cancelModal=true; cancelId={{ $apt->id }}"
                                                class="text-red-500 hover:text-red-700 text-sm font-medium transition">Cancel</button>
                                        @endif
                                        @if($apt->payment_status != 'paid')
                                            <button @click="paymentModal=true; selectedAppointment={{ $apt->id }}"
                                                class="text-violet-500 hover:text-violet-700 text-sm font-medium transition">Pay</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Past -->
    <div x-show="tab==='past'">
        @if($past->isEmpty())
            <div class="bg-white border border-gray-100 rounded-2xl py-20 text-center">
                <p class="text-gray-400 text-sm">No past appointments yet.</p>
            </div>
        @else
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th
                                class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                                Service</th>
                            <th
                                class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                                Date & Time</th>
                            <th
                                class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                                Location</th>
                            <th
                                class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                                Price</th>
                            <th
                                class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                                Payment</th>
                            <th
                                class="px-6 py-3.5 text-left text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                                Status</th>
                         </>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($past as $apt)
                            @php $c = $statusColors[$apt->status] ?? 'gray'; @endphp
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $apt->service->name }}</td>
                                <td class="px-6 py-4 text-gray-400">{{ $apt->appointment_date->format('M d, Y · g:i A') }}</td>
                                <td class="px-6 py-4 text-gray-400">
                                    {{ $apt->preferred_location ?? ($apt->location_address ? substr($apt->location_address, 0, 40) : 'Not specified') }}
                                </td>
                                <td class="px-6 py-4 text-gray-500 font-medium">₱{{ number_format($apt->service->price, 2) }}</td>
                                <td class="px-6 py-4">
                                    @if($apt->payment_status == 'paid')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-emerald-50 text-emerald-700">
                                            Paid
                                        </span>
                                    @elseif($apt->payment_status == 'partial')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-amber-50 text-amber-700">
                                            Partial (₱{{ number_format($apt->amount_paid, 2) }})
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-50 text-red-700">
                                            Unpaid
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs font-semibold bg-{{ $c }}-50 text-{{ $c }}-700">{{ ucfirst($apt->status) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Cancel Modal -->
    <div x-show="cancelModal" x-transition
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div @click.outside="cancelModal=false" class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center mb-4">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="font-display font-bold text-gray-900 text-lg mb-1">Cancel Appointment?</h3>
            <p class="text-gray-400 text-sm mb-4">Please provide a reason for cancellation.</p>
            <form method="POST" :action="`/appointments/${cancelId}/cancel`">
                @csrf @method('DELETE')
                <textarea name="cancellation_reason" rows="3" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-400 resize-none transition"
                    placeholder="e.g. Schedule conflict, feeling unwell..."></textarea>
                <div class="mt-4 flex gap-3">
                    <button type="submit"
                        class="flex-1 bg-red-500 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-red-600 transition">Confirm
                        Cancel</button>
                    <button type="button" @click="cancelModal=false"
                        class="px-5 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-500 hover:bg-gray-50 transition font-medium">Go
                        Back</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Payment Modal -->
    <div x-show="paymentModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40">
        <div @click.outside="paymentModal=false" class="bg-white rounded-2xl max-w-md w-full p-6">
            <h3 class="font-bold text-lg text-gray-900 mb-2">Update Payment</h3>
            <form method="POST" :action="`/appointments/${selectedAppointment}/payment`">
                @csrf
                @method('PATCH')
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <select name="payment_method" required class="w-full border border-gray-200 rounded-lg px-3 py-2">
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="gcash">GCash</option>
                            <option value="paymaya">PayMaya</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount Paid (₱)</label>
                        <input type="number" name="amount_paid" step="0.01" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2"
                            placeholder="0.00">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reference Number</label>
                        <input type="text" name="payment_reference"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2"
                            placeholder="GCash/Bank reference #">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="payment_notes" rows="2"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2"
                            placeholder="Additional payment notes..."></textarea>
                    </div>
                </div>
                
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex-1 bg-violet-600 text-white py-2 rounded-lg font-semibold">Submit Payment</button>
                    <button type="button" @click="paymentModal=false" class="px-4 py-2 border border-gray-200 rounded-lg">Cancel</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection