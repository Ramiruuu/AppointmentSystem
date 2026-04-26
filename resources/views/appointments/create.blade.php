@extends('layouts.app')
@section('title', 'Book Appointment')

@section('content')
<div class="max-w-3xl">

    <div class="mb-6">
        <h1 class="text-xl font-semibold text-gray-900">Book an appointment</h1>
        <p class="text-sm text-gray-400 mt-1">Choose your service, location, and preferred time.</p>
    </div>

    @if($errors->any())
        <div class="mb-5 bg-red-50 border-l-4 border-red-400 rounded-lg px-5 py-4">
            @foreach($errors->all() as $error)
                <p class="text-sm text-red-700">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 bg-red-50 border-l-4 border-red-400 rounded-lg px-5 py-4">
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('appointments.store') }}">
        @csrf

        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

            {{-- SERVICE --}}
            <div class="px-6 py-5 border-b border-gray-100">
                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-2">Service</label>
                <select name="service_id" required
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Select a service</option>
                    @foreach($services as $s)
                        <option value="{{ $s->id }}" {{ old('service_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }} — {{ $s->duration_minutes }} min — ₱{{ number_format($s->price, 2) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- HOSPITAL --}}
            <div class="px-6 py-5 border-b border-gray-100">
                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-2">Hospital / Clinic</label>
                <select name="hospital_id" id="hospitalSelect" required
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Select a hospital</option>
                    @foreach($hospitals as $h)
                        <option value="{{ $h->id }}" {{ old('hospital_id') == $h->id ? 'selected' : '' }}>
                            {{ $h->name }} — {{ $h->address }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- DATE --}}
            <div class="px-6 py-5 border-b border-gray-100">
                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-2">Date</label>
                <input type="date" name="appointment_date" id="appointmentDate" required
                    min="{{ now()->format('Y-m-d') }}"
                    value="{{ old('appointment_date') }}"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            {{-- TIME SLOTS --}}
            <div class="px-6 py-5 border-b border-gray-100">
                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Time Slot</label>
                <p class="text-xs text-gray-400 mb-3">Select a hospital and date first to load available slots.</p>
                <div id="timeSlotsContainer" class="grid grid-cols-1 md:grid-cols-2 gap-3"></div>
                <input type="hidden" name="time_slot" id="selectedTimeSlot" required>
            </div>

            {{-- PAYMENT METHOD --}}
            <div class="px-6 py-5">
                <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-3">Payment Method</label>
                <div class="grid grid-cols-2 gap-3">
                    @foreach([
                        ['value' => 'cash',          'label' => 'Cash (Pay at clinic)'],
                        ['value' => 'gcash',         'label' => 'GCash'],
                        ['value' => 'bank_transfer', 'label' => 'Bank Transfer'],
                        ['value' => 'paymaya',       'label' => 'PayMaya'],
                    ] as $method)
                        <label class="flex items-center gap-3 px-4 py-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-indigo-50 hover:border-indigo-300 transition has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                            <input type="radio" name="payment_method" value="{{ $method['value'] }}"
                                class="accent-indigo-700"
                                {{ (old('payment_method', 'cash') === $method['value']) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">{{ $method['label'] }}</span>
                        </label>
                    @endforeach
                </div>

                <div id="referenceField" class="hidden mt-4">
                    <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-2">Reference Number</label>
                    <input type="text" name="payment_reference"
                        value="{{ old('payment_reference') }}"
                        placeholder="Enter transaction reference number"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
        </div>

        {{-- SUBMIT --}}
        <div class="flex gap-3 mt-5">
            <button type="submit"
                class="flex-1 bg-indigo-900 text-indigo-50 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-800 transition">
                Confirm Booking
            </button>
            <a href="{{ route('appointments.index') }}"
                class="px-5 py-2.5 border border-gray-200 text-gray-500 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                Cancel
            </a>
        </div>

        <input type="hidden" name="location_latitude"  id="locationLatitude">
        <input type="hidden" name="location_longitude" id="locationLongitude">
        <input type="hidden" name="location_address"   id="locationAddress">
        <input type="hidden" name="preferred_location" id="preferredLocation">
    </form>
</div>

<script>
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const ref = document.getElementById('referenceField');
            ref.classList.toggle('hidden', this.value === 'cash');
        });
    });

    document.getElementById('hospitalSelect')?.addEventListener('change', function () {
        const hospitals = @json($hospitals);
        const h = hospitals.find(h => h.id == this.value);
        if (h) {
            document.getElementById('locationLatitude').value  = h.latitude  || '';
            document.getElementById('locationLongitude').value = h.longitude || '';
            document.getElementById('locationAddress').value   = h.address   || '';
            document.getElementById('preferredLocation').value = h.name      || '';
        }
        if (document.getElementById('appointmentDate')?.value) fetchTimeSlots();
    });

    function fetchTimeSlots() {
        const date       = document.getElementById('appointmentDate')?.value;
        const hospitalId = document.getElementById('hospitalSelect')?.value || '';
        if (!date) return;

        const container = document.getElementById('timeSlotsContainer');
        container.innerHTML = '<div class="col-span-2 text-center py-6 text-sm text-gray-400">Loading slots...</div>';

        fetch(`/get-time-slots?date=${date}&hospital_id=${hospitalId}`)
            .then(r => r.json())
            .then(slots => {
                container.innerHTML = '';
                const arr = Object.values(slots);
                if (!arr.length || slots.error) {
                    container.innerHTML = '<div class="col-span-2 text-center py-6 text-sm text-red-400">No slots available.</div>';
                    return;
                }
                arr.forEach(slot => {
                    const isFull = slot.is_full;
                    const barColor = slot.percentage >= 80 ? 'bg-red-400' : slot.percentage >= 50 ? 'bg-amber-400' : 'bg-green-500';
                    const avColor  = isFull ? 'text-red-600' : slot.percentage >= 50 ? 'text-amber-600' : 'text-green-700';

                    const div = document.createElement('div');
                    div.className = `border rounded-lg p-4 transition-all
                        ${isFull ? 'border-gray-200 bg-gray-50 opacity-50 cursor-not-allowed' : 'border-gray-200 cursor-pointer hover:border-indigo-400 hover:bg-indigo-50'}`;

                    div.innerHTML = `
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <div class="text-sm font-medium text-gray-900">${slot.label}</div>
                                <div class="text-xs text-gray-400 mt-0.5">${slot.start} – ${slot.end}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-semibold ${avColor}">${slot.available} / 10 available</div>
                                <div class="text-xs text-gray-400">${slot.total_booked} booked</div>
                            </div>
                        </div>
                        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="${barColor} h-full rounded-full" style="width: ${slot.percentage}%"></div>
                        </div>
                        ${isFull ? '<div class="text-xs text-red-500 mt-2 font-semibold text-center">SLOT FULL</div>' : ''}
                    `;

                    if (!isFull) {
                        div.onclick = () => {
                            document.getElementById('selectedTimeSlot').value = slot.key;
                            container.querySelectorAll('div[class*="border"]').forEach(el => {
                                el.classList.remove('border-indigo-600', 'bg-indigo-50');
                                el.classList.add('border-gray-200');
                            });
                            div.classList.remove('border-gray-200');
                            div.classList.add('border-indigo-600', 'bg-indigo-50');
                        };
                    }
                    container.appendChild(div);
                });
            })
            .catch(() => {
                container.innerHTML = '<div class="col-span-2 text-center py-6 text-sm text-red-400">Error loading slots.</div>';
            });
    }

    document.getElementById('appointmentDate')?.addEventListener('change', fetchTimeSlots);
    if (document.getElementById('appointmentDate')?.value) fetchTimeSlots();
</script>
@endsection