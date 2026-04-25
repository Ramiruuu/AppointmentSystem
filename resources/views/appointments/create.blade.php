@extends('layouts.app')
@section('title', 'Book Appointment')

@section('content')
<div class="max-w-2xl mx-auto" x-data="{
    selectedService: '{{ old('service_id') }}',
    selectedDate: '{{ old('appointment_date') }}',
    services: {!! Js::from($services->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'duration' => $s->duration_minutes, 'price' => $s->price])->values()) !!},
    get minDate() { let d=new Date(); d.setHours(d.getHours()+1); return d.toISOString().slice(0,16); },
    get service() { return this.services.find(x => x.id == this.selectedService); },
    get formattedDate() {
        if(!this.selectedDate) return null;
        return new Date(this.selectedDate).toLocaleString('en-US',{dateStyle:'long',timeStyle:'short'});
    }
}">
    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">
        <div class="px-8 pt-8 pb-6 border-b border-gray-50">
            <h2 class="font-display font-bold text-xl text-gray-900">Book an Appointment</h2>
            <p class="text-gray-400 text-sm mt-1">Choose a service and pick your preferred time slot.</p>
        </div>

        @if($errors->any())
        <div class="mx-8 mt-6 bg-red-50 border border-red-100 rounded-xl p-4">
            @foreach($errors->all() as $error)
            <p class="text-red-600 text-sm">• {{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('appointments.store') }}" class="px-8 py-6 space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Select Service</label>
                <select name="service_id" x-model="selectedService" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white text-gray-900 transition">
                    <option value="">— Choose a service —</option>
                    @foreach($services as $s)
                    <option value="{{ $s->id }}" {{ old('service_id')==$s->id?'selected':'' }}>
                        {{ $s->name }} ({{ $s->duration_minutes }} min{{ $s->price ? ' · $'.number_format($s->price,2) : '' }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Date & Time</label>
                <input type="datetime-local" name="appointment_date" x-model="selectedDate" :min="minDate" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-gray-900 transition">
                <p class="text-gray-400 text-xs mt-1.5">Appointments must be at least 1 hour from now.</p>
            </div>

            <!-- Preview -->
            <div x-show="selectedService && selectedDate" x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                 class="bg-gradient-to-br from-violet-600 to-purple-700 rounded-xl p-5 text-white">
                <p class="text-[10px] font-bold tracking-widest uppercase opacity-60 mb-3">Booking Summary</p>
                <p class="font-display font-bold text-lg" x-text="service?.name"></p>
                <p class="text-white/60 text-sm mt-0.5" x-text="service?.duration + ' minute session'"></p>
                <div class="mt-4 pt-4 border-t border-white/15 grid grid-cols-2 gap-y-2 text-sm">
                    <span class="text-white/60">Date & Time</span>
                    <span class="text-right font-medium" x-text="formattedDate"></span>
                    <span class="text-white/60">Price</span>
                    <span class="text-right font-medium" x-text="service?.price ? '$' + parseFloat(service.price).toFixed(2) : 'Free'"></span>
                    <span class="text-white/60">Status after booking</span>
                    <span class="text-right font-medium">Pending</span>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-violet-600 text-white py-3 rounded-xl font-semibold text-sm hover:bg-violet-700 transition">
                    Confirm Booking
                </button>
                <a href="{{ route('appointments.index') }}" class="px-5 py-3 border border-gray-200 text-gray-500 rounded-xl text-sm hover:bg-gray-50 transition font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection