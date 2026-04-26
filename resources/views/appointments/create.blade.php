@extends('layouts.app')
@section('title', 'Book Appointment')

@section('content')
<style>
    /* ADDED: responsive fixes only */
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr !important;
            gap: 16px !important;
        }
        .payment-grid {
            grid-template-columns: 1fr 1fr !important;
        }
        body, .container, [style*="max-width:860px"] {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }
        .form-card {
            padding: 14px 16px !important;
        }
        .summary-grid {
            grid-template-columns: 1fr !important;
            gap: 8px !important;
        }
    }
    @media (max-width: 480px) {
        .payment-grid {
            grid-template-columns: 1fr !important;
        }
        .field-input {
            font-size: 14px !important;
            padding: 10px !important;
        }
        .btn-primary {
            padding: 12px !important;
        }
    }
    /* preserve your original styles */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        align-items: start;
    }
    .payment-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }
    @media (max-width: 700px) {
        .form-grid { grid-template-columns: 1fr; }
        .payment-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 400px) {
        .payment-grid { grid-template-columns: 1fr; }
    }
    .form-card {
        background: #fff;
        border: 0.5px solid #e8e8f0;
        border-radius: 12px;
        padding: 18px 20px;
    }
    .field-label {
        font-size: 10px;
        font-weight: 500;
        color: #888780;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 8px;
    }
    .field-input {
        width: 100%;
        border: 0.5px solid #e8e8f0;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 13px;
        color: #1a1a2e;
        background: #F7F7FB;
        outline: none;
        transition: border-color 0.15s;
    }
    .field-input:focus { border-color: #7F77DD; }
</style>

<div style="max-width:860px; width:100%; margin:0 auto;">

    <div style="margin-bottom:22px;">
        <div style="font-size:clamp(16px,2.5vw,20px); font-weight:500; color:#1a1a2e; letter-spacing:-0.02em;">Book an appointment</div>
        <div style="font-size:13px; color:#888780; margin-top:4px;">Choose your service, location, date, and preferred time slot.</div>
    </div>

    @if($errors->any())
        <div style="background:#FCEBEB; border-left:3px solid #E24B4A; border-radius:0 8px 8px 0; padding:12px 16px; margin-bottom:18px;">
            @foreach($errors->all() as $error)
                <div style="font-size:13px; color:#791F1F;">{{ $error }}</div>
            @endforeach
        </div>
    @endif
    @if(session('error'))
        <div style="background:#FCEBEB; border-left:3px solid #E24B4A; border-radius:0 8px 8px 0; padding:12px 16px; margin-bottom:18px;">
            <div style="font-size:13px; color:#791F1F;">{{ session('error') }}</div>
        </div>
    @endif

    <form method="POST" action="{{ route('appointments.store') }}">
        @csrf

        <div class="form-grid">

            {{-- LEFT --}}
            <div style="display:flex; flex-direction:column; gap:12px;">

                <div class="form-card">
                    <div class="field-label">Service</div>
                    <select name="service_id" id="serviceSelect" required class="field-input">
                        <option value="">Select a service</option>
                        @foreach($services as $s)
                            <option value="{{ $s->id }}" data-price="{{ $s->price }}" data-duration="{{ $s->duration_minutes }}"
                                {{ old('service_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }} — {{ $s->duration_minutes }} min — ₱{{ number_format($s->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-card">
                    <div class="field-label">Hospital / Clinic</div>
                    <select name="hospital_id" id="hospitalSelect" required class="field-input">
                        <option value="">Select a hospital</option>
                        @foreach($hospitals as $h)
                            <option value="{{ $h->id }}" {{ old('hospital_id') == $h->id ? 'selected' : '' }}>
                                {{ $h->name }} — {{ $h->address }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-card">
                    <div class="field-label">Date</div>
                    <input type="date" name="appointment_date" id="appointmentDate" required
                        min="{{ now()->format('Y-m-d') }}"
                        value="{{ old('appointment_date') }}"
                        class="field-input">
                </div>

                <div class="form-card">
                    <div class="field-label">Payment Method</div>
                    <div class="payment-grid" id="paymentOptions">
                        @foreach([
                            ['value' => 'cash',          'label' => 'Cash'],
                            ['value' => 'gcash',         'label' => 'GCash'],
                            ['value' => 'bank_transfer', 'label' => 'Bank Transfer'],
                            ['value' => 'paymaya',       'label' => 'PayMaya'],
                        ] as $method)
                            <label id="label_{{ $method['value'] }}"
                                style="display:flex; align-items:center; gap:8px; padding:9px 12px; border-radius:8px; cursor:pointer; transition:all 0.15s;
                                {{ (old('payment_method','cash') === $method['value']) ? 'border:1.5px solid #3C3489; background:#EEEDFE;' : 'border:0.5px solid #e8e8f0; background:#F7F7FB;' }}">
                                <input type="radio" name="payment_method" value="{{ $method['value'] }}"
                                    {{ (old('payment_method','cash') === $method['value']) ? 'checked' : '' }}
                                    style="accent-color:#26215C; flex-shrink:0;">
                                <span style="font-size:12px; color:{{ (old('payment_method','cash') === $method['value']) ? '#26215C' : '#888780' }}; font-weight:{{ (old('payment_method','cash') === $method['value']) ? '500' : '400' }}; white-space:nowrap;">
                                    {{ $method['label'] }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <div id="referenceField" style="display:none; margin-top:12px;">
                        <div class="field-label">Reference Number</div>
                        <input type="text" name="payment_reference" value="{{ old('payment_reference') }}"
                            placeholder="Enter transaction reference"
                            class="field-input">
                    </div>
                </div>

            </div>

            {{-- RIGHT --}}
            <div style="display:flex; flex-direction:column; gap:12px;">

                <div class="form-card">
                    <div class="field-label">Available Time Slots</div>
                    <div style="font-size:11px; color:#888780; margin-bottom:12px;">Select a hospital and date to see slots.</div>
                    <div id="timeSlotsContainer" style="display:flex; flex-direction:column; gap:8px;"></div>
                    <input type="hidden" name="time_slot" id="selectedTimeSlot" required>
                </div>

                <div style="background:#EEEDFE; border-radius:12px; padding:18px 20px;">
                    <div style="font-size:10px; font-weight:500; color:#534AB7; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:14px;">Booking Summary</div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                        @foreach([
                            ['id' => 'sumService',  'label' => 'Service'],
                            ['id' => 'sumLocation', 'label' => 'Location'],
                            ['id' => 'sumDate',     'label' => 'Date'],
                            ['id' => 'sumSlot',     'label' => 'Time Slot'],
                            ['id' => 'sumPayment',  'label' => 'Payment'],
                            ['id' => 'sumAmount',   'label' => 'Amount'],
                        ] as $row)
                            <div>
                                <div style="font-size:10px; color:#7F77DD; margin-bottom:3px;">{{ $row['label'] }}</div>
                                <div id="{{ $row['id'] }}" style="font-size:12px; font-weight:500; color:#26215C; word-break:break-word;">—</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit"
                    style="width:100%; padding:13px; background:#26215C; color:#EEEDFE; border:none; border-radius:10px; font-size:13px; font-weight:500; cursor:pointer; transition:background 0.15s;"
                    onmouseover="this.style.background='#3C3489'" onmouseout="this.style.background='#26215C'">
                    Confirm Booking
                </button>

                <a href="{{ route('appointments.index') }}"
                    style="display:block; text-align:center; font-size:12px; color:#888780; text-decoration:none; padding:4px 0;">
                    Cancel and go back
                </a>

            </div>
        </div>

        <input type="hidden" name="location_latitude"  id="locationLatitude">
        <input type="hidden" name="location_longitude" id="locationLongitude">
        <input type="hidden" name="location_address"   id="locationAddress">
        <input type="hidden" name="preferred_location" id="preferredLocation">
    </form>
</div>

<script>
    const slotNames = {
        morning_1:   'Morning · 7:00 AM – 9:00 AM',
        morning_2:   'Late Morning · 10:00 AM – 12:00 PM',
        afternoon_1: 'Afternoon · 1:00 PM – 3:00 PM',
        afternoon_2: 'Late Afternoon · 4:00 PM – 6:00 PM',
    };

    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function () {
            document.querySelectorAll('input[name="payment_method"]').forEach(r => {
                const lbl = document.getElementById('label_' + r.value);
                lbl.style.border = '0.5px solid #e8e8f0';
                lbl.style.background = '#F7F7FB';
                lbl.querySelector('span').style.color = '#888780';
                lbl.querySelector('span').style.fontWeight = '400';
            });
            const active = document.getElementById('label_' + this.value);
            active.style.border = '1.5px solid #3C3489';
            active.style.background = '#EEEDFE';
            active.querySelector('span').style.color = '#26215C';
            active.querySelector('span').style.fontWeight = '500';

            const map = { cash:'Cash', gcash:'GCash', bank_transfer:'Bank Transfer', paymaya:'PayMaya' };
            document.getElementById('sumPayment').textContent = map[this.value] || this.value;
            document.getElementById('referenceField').style.display =
                ['gcash','bank_transfer','paymaya'].includes(this.value) ? 'block' : 'none';
        });
    });

    document.getElementById('hospitalSelect').addEventListener('change', function () {
        const hospitals = @json($hospitals);
        const h = hospitals.find(h => h.id == this.value);
        if (h) {
            document.getElementById('locationLatitude').value  = h.latitude  || '';
            document.getElementById('locationLongitude').value = h.longitude || '';
            document.getElementById('locationAddress').value   = h.address   || '';
            document.getElementById('preferredLocation').value = h.name      || '';
            document.getElementById('sumLocation').textContent = h.name;
        }
        if (document.getElementById('appointmentDate').value) fetchTimeSlots();
    });

    document.getElementById('serviceSelect').addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        document.getElementById('sumService').textContent = opt.text.split(' — ')[0] || '—';
        document.getElementById('sumAmount').textContent  = opt.dataset.price ? '₱' + parseFloat(opt.dataset.price).toFixed(2) : '—';
    });

    document.getElementById('appointmentDate').addEventListener('change', function () {
        const d = new Date(this.value + 'T00:00:00');
        document.getElementById('sumDate').textContent = d.toLocaleDateString('en-PH', { month:'long', day:'numeric', year:'numeric' });
        fetchTimeSlots();
    });

    function fetchTimeSlots() {
        const date       = document.getElementById('appointmentDate').value;
        const hospitalId = document.getElementById('hospitalSelect').value || '';
        if (!date) return;

        const container = document.getElementById('timeSlotsContainer');
        container.innerHTML = '<div style="text-align:center; padding:20px; font-size:13px; color:#888780;">Loading slots...</div>';

        fetch(`/get-time-slots?date=${date}&hospital_id=${hospitalId}`)
            .then(r => r.json())
            .then(slots => {
                container.innerHTML = '';
                const arr = Object.values(slots);
                if (!arr.length || slots.error) {
                    container.innerHTML = '<div style="text-align:center; padding:20px; font-size:13px; color:#E24B4A;">No slots available.</div>';
                    return;
                }
                arr.forEach(slot => {
                    const isFull = slot.is_full;
                    const barColor = slot.percentage >= 80 ? '#E24B4A' : slot.percentage >= 50 ? '#EF9F27' : '#639922';
                    const avColor  = isFull ? '#A32D2D' : slot.percentage >= 80 ? '#A32D2D' : slot.percentage >= 50 ? '#854F0B' : '#27500A';

                    const div = document.createElement('div');
                    div.style.cssText = `border:0.5px solid #e8e8f0; border-radius:10px; padding:12px 14px; background:#F7F7FB; transition:all 0.15s; ${isFull ? 'opacity:0.5; cursor:not-allowed;' : 'cursor:pointer;'}`;

                    div.innerHTML = `
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:8px;">
                            <div>
                                <div style="font-size:13px; font-weight:500; color:#1a1a2e;">${slot.label.split('(')[0].trim()}</div>
                                <div style="font-size:11px; color:#888780; margin-top:1px;">${slot.start} – ${slot.end}</div>
                            </div>
                            <div style="text-align:right; flex-shrink:0; margin-left:8px;">
                                <div style="font-size:11px; font-weight:500; color:${avColor};">${slot.available} / 10 slots</div>
                                <div style="font-size:10px; color:#888780; margin-top:1px;">${slot.total_booked} booked</div>
                            </div>
                        </div>
                        <div style="height:3px; background:#e8e8f0; border-radius:99px; overflow:hidden;">
                            <div style="width:${slot.percentage}%; height:100%; background:${barColor}; border-radius:99px;"></div>
                        </div>
                        ${isFull ? '<div style="font-size:11px; color:#A32D2D; margin-top:6px; font-weight:500; text-align:center;">Slot full</div>' : ''}
                    `;

                    if (!isFull) {
                        div.onclick = () => {
                            document.getElementById('selectedTimeSlot').value = slot.key;
                            container.querySelectorAll('div[style*="cursor:pointer"]').forEach(el => {
                                el.style.border = '0.5px solid #e8e8f0';
                                el.style.background = '#F7F7FB';
                            });
                            div.style.border = '1.5px solid #3C3489';
                            div.style.background = '#EEEDFE';
                            document.getElementById('sumSlot').textContent = slotNames[slot.key] || slot.label;
                        };
                    }
                    container.appendChild(div);
                });
            })
            .catch(() => {
                container.innerHTML = '<div style="text-align:center; padding:20px; font-size:13px; color:#E24B4A;">Error loading slots.</div>';
            });
    }

    if (document.getElementById('appointmentDate').value) fetchTimeSlots();
</script>
@endsection