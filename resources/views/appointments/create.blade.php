@extends('layouts.app')
@section('title', 'Book Appointment')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-8 pt-8 pb-6 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-900">Book an Appointment</h2>
            <p class="text-sm text-gray-500 mt-1">Choose a service, hospital, date, and time slot.</p>
        </div>

        @if($errors->any())
            <div class="mx-8 mt-4 p-4 bg-red-50 border border-red-100 rounded-xl">
                @foreach($errors->all() as $error)
                    <p class="text-red-600 text-sm">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if(session('error'))
            <div class="mx-8 mt-4 p-4 bg-red-50 border border-red-100 rounded-xl">
                <p class="text-red-600 text-sm">• {{ session('error') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('appointments.store') }}" class="px-8 py-6">
            @csrf

            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Select Service</label>
                    <select name="service_id" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white">
                        <option value="">Choose a service</option>
                        @foreach($services as $s)
                            <option value="{{ $s->id }}" {{ old('service_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }} ({{ $s->duration_minutes }} min - ₱{{ number_format($s->price, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Select Hospital/Clinic</label>
                    <select name="hospital_id" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white"
                        id="hospitalSelect">
                        <option value="">Choose a hospital</option>
                        @foreach($hospitals as $h)
                            <option value="{{ $h->id }}" {{ old('hospital_id') == $h->id ? 'selected' : '' }}>
                                {{ $h->name }} - {{ $h->address }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Select Date</label>
                    <input type="date" name="appointment_date" id="appointmentDate" required
                        min="{{ now()->format('Y-m-d') }}"
                        value="{{ old('appointment_date') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Select Time Slot</label>
                    <div id="timeSlotsContainer" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
                    <input type="hidden" name="time_slot" id="selectedTimeSlot" required>
                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Payment Method</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="cash" class="text-violet-600" checked>
                            <span class="text-sm">Cash (Pay at clinic)</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="bank_transfer" class="text-violet-600">
                            <span class="text-sm">Bank Transfer</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="gcash" class="text-violet-600">
                            <span class="text-sm">GCash</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="paymaya" class="text-violet-600">
                            <span class="text-sm">PayMaya</span>
                        </label>
                    </div>

                    <div id="referenceField" class="hidden mt-4">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Reference Number</label>
                        <input type="text" name="payment_reference"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm"
                            placeholder="Enter transaction reference number">
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit"
                        class="flex-1 bg-violet-600 text-white py-3 rounded-xl font-semibold text-sm hover:bg-violet-700 transition">
                        Confirm Booking
                    </button>
                    <a href="{{ route('appointments.index') }}"
                        class="px-6 py-3 border border-gray-200 text-gray-600 rounded-xl font-semibold text-sm hover:bg-gray-50 transition">
                        Cancel
                    </a>
                </div>
            </div>

            <input type="hidden" name="location_latitude" id="locationLatitude">
            <input type="hidden" name="location_longitude" id="locationLongitude">
            <input type="hidden" name="location_address" id="locationAddress">
            <input type="hidden" name="preferred_location" id="preferredLocation">
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const referenceField = document.getElementById('referenceField');
            if (this.value === 'bank_transfer' || this.value === 'gcash' || this.value === 'paymaya') {
                referenceField.classList.remove('hidden');
            } else {
                referenceField.classList.add('hidden');
            }
        });
    });

    document.querySelector('select[name="hospital_id"]')?.addEventListener('change', function() {
        const hospitals = @json($hospitals);
        const hospital = hospitals.find(h => h.id == this.value);
        if (hospital) {
            document.getElementById('locationLatitude').value = hospital.latitude || '';
            document.getElementById('locationLongitude').value = hospital.longitude || '';
            document.getElementById('locationAddress').value = hospital.address || '';
            document.getElementById('preferredLocation').value = hospital.name || '';
        }
    });

    function fetchTimeSlots() {
        const date = document.getElementById('appointmentDate')?.value;
        const hospitalId = document.querySelector('select[name="hospital_id"]')?.value || '';
        
        if (!date) return;
        
        const container = document.getElementById('timeSlotsContainer');
        container.innerHTML = '<div class="col-span-2 text-center py-8 text-gray-400">Loading time slots...</div>';
        
        fetch(`/get-time-slots?date=${date}&hospital_id=${hospitalId}`)
            .then(response => response.json())
            .then(slots => {
                container.innerHTML = '';
                
                if (slots.error) {
                    container.innerHTML = '<div class="col-span-2 text-center py-8 text-red-500">Error loading slots</div>';
                    return;
                }
                
                const slotsArray = Object.values(slots);
                if (slotsArray.length === 0) {
                    container.innerHTML = '<div class="col-span-2 text-center py-8 text-gray-400">No time slots available</div>';
                    return;
                }
                
                slotsArray.forEach(slot => {
                    const slotDiv = document.createElement('div');
                    slotDiv.className = `p-4 border-2 rounded-xl cursor-pointer transition-all ${slot.is_full ? 'bg-gray-100 border-gray-200 cursor-not-allowed opacity-60' : 'hover:border-violet-500 hover:bg-violet-50 border-gray-200'}`;
                    
                    let barColor = 'bg-green-500';
                    if (slot.percentage >= 80) barColor = 'bg-red-500';
                    else if (slot.percentage >= 50) barColor = 'bg-yellow-500';
                    
                    slotDiv.innerHTML = `
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <div class="font-semibold text-gray-900">${slot.label}</div>
                                <div class="text-xs text-gray-500 mt-1">${slot.start} - ${slot.end}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-bold ${slot.is_full ? 'text-red-600' : 'text-green-600'}">
                                    ${slot.available} / 10 available
                                </div>
                                <div class="text-xs text-gray-400">${slot.total_booked} booked</div>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="${barColor} h-2.5 rounded-full" style="width: ${slot.percentage}%"></div>
                        </div>
                        ${slot.is_full ? '<div class="text-xs text-red-500 mt-3 text-center font-medium">SLOT FULL</div>' : ''}
                    `;
                    
                    if (!slot.is_full) {
                        slotDiv.onclick = () => {
                            document.getElementById('selectedTimeSlot').value = slot.key;
                            document.querySelectorAll('#timeSlotsContainer > div').forEach(el => {
                                el.classList.remove('border-violet-500', 'bg-violet-50');
                                el.classList.add('border-gray-200');
                            });
                            slotDiv.classList.remove('border-gray-200');
                            slotDiv.classList.add('border-violet-500', 'bg-violet-50');
                        };
                    }
                    container.appendChild(slotDiv);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                container.innerHTML = '<div class="col-span-2 text-center py-8 text-red-500">Error loading time slots</div>';
            });
    }

    document.getElementById('appointmentDate')?.addEventListener('change', function() {
        fetchTimeSlots();
    });

    document.querySelector('select[name="hospital_id"]')?.addEventListener('change', function() {
        if (document.getElementById('appointmentDate')?.value) {
            fetchTimeSlots();
        }
    });

    if (document.getElementById('appointmentDate')?.value) {
        fetchTimeSlots();
    }
</script>
@endsection