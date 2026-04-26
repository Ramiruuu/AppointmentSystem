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

<style>
    .dash-wrap { display:flex; flex-direction:column; gap:20px; width:100%; }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        width: 100%;
    }

    .appt-card-inner {
        display: grid;
        grid-template-columns: 50px 1fr auto;
        gap: 14px;
        align-items: center;
    }

    .badge-col {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 5px;
        flex-shrink: 0;
    }

    .admin-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    @media (max-width: 1000px) {
        .stat-grid { grid-template-columns: repeat(2, 1fr); }
        .admin-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 560px) {
        .stat-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .appt-card-inner { grid-template-columns: 44px 1fr; }
        .badge-col { display: none; }
        .mobile-badges { display: flex !important; }
    }

    @media (min-width: 561px) {
        .mobile-badges { display: none !important; }
    }
</style>

<div class="dash-wrap">

    {{-- SUCCESS --}}
    @if(session('success'))
        <div style="background:#EAF3DE; border-left:3px solid #639922; border-radius:0 8px 8px 0; padding:12px 18px; display:flex; justify-content:space-between; align-items:flex-start; gap:12px; width:100%;">
            <div>
                <div style="font-size:13px; font-weight:500; color:#27500A;">Appointment booked successfully!</div>
                <div style="font-size:12px; color:#3B6D11; margin-top:3px;">{{ session('success') }}</div>
            </div>
            <button onclick="this.closest('div').remove()" style="background:none; border:none; font-size:20px; color:#639922; cursor:pointer; line-height:1; flex-shrink:0;">&times;</button>
        </div>
    @endif

    {{-- ERROR --}}
    @if(session('error'))
        <div style="background:#FCEBEB; border-left:3px solid #E24B4A; border-radius:0 8px 8px 0; padding:12px 18px; display:flex; justify-content:space-between; align-items:center; gap:12px; width:100%;">
            <div style="font-size:13px; color:#791F1F;">{{ session('error') }}</div>
            <button onclick="this.closest('div').remove()" style="background:none; border:none; font-size:20px; color:#E24B4A; cursor:pointer; line-height:1; flex-shrink:0;">&times;</button>
        </div>
    @endif

    {{-- HEADER --}}
    <div style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:flex-start; gap:12px; width:100%;">
        <div>
            <div style="font-size:22px; font-weight:500; color:#1a1a2e; letter-spacing:-0.02em; line-height:1.3;">Good day, {{ Auth::user()->name }}</div>
            <div style="font-size:13px; color:#888780; margin-top:5px;">{{ now()->format('l, F d') }} · Here's your appointment overview</div>
        </div>
        <a href="{{ route('appointments.create') }}"
            style="padding:10px 22px; background:#26215C; color:#EEEDFE; border-radius:8px; font-size:13px; font-weight:500; text-decoration:none; white-space:nowrap; flex-shrink:0; display:inline-block;"
            onmouseover="this.style.background='#3C3489'" onmouseout="this.style.background='#26215C'">
            + Book Appointment
        </a>
    </div>

    {{-- STAT CARDS --}}
    <div class="stat-grid">
        <div style="background:#fff; border:1px solid #eeeef2; border-radius:14px; padding:20px 22px;">
            <div style="font-size:10px; font-weight:500; color:#888780; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:10px;">Total</div>
            <div style="font-size:34px; font-weight:500; color:#1a1a2e; letter-spacing:-0.03em; line-height:1;">{{ $totalAppointments }}</div>
            <div style="font-size:12px; color:#888780; margin-top:8px;">all time</div>
        </div>
        <div style="background:#EEEDFE; border-radius:14px; padding:20px 22px;">
            <div style="font-size:10px; font-weight:500; color:#534AB7; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:10px;">Upcoming</div>
            <div style="font-size:34px; font-weight:500; color:#26215C; letter-spacing:-0.03em; line-height:1;">{{ $upcomingAppointments }}</div>
            <div style="font-size:12px; color:#7F77DD; margin-top:8px;">scheduled</div>
        </div>
        <div style="background:#EAF3DE; border-radius:14px; padding:20px 22px;">
            <div style="font-size:10px; font-weight:500; color:#3B6D11; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:10px;">Completed</div>
            <div style="font-size:34px; font-weight:500; color:#173404; letter-spacing:-0.03em; line-height:1;">{{ $completedAppointments }}</div>
            <div style="font-size:12px; color:#639922; margin-top:8px;">done</div>
        </div>
        <div style="background:#FCEBEB; border-radius:14px; padding:20px 22px;">
            <div style="font-size:10px; font-weight:500; color:#A32D2D; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:10px;">Cancelled</div>
            <div style="font-size:34px; font-weight:500; color:#501313; letter-spacing:-0.03em; line-height:1;">{{ $cancelledAppointments }}</div>
            <div style="font-size:12px; color:#E24B4A; margin-top:8px;">cancelled</div>
        </div>
    </div>

    {{-- ADMIN CHARTS --}}
    @if(Auth::user()->isAdmin())
        @php
            $months    = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            $yearTotal = \App\Models\Appointment::whereYear('appointment_date', now()->year)->count();
            $allTotal  = \App\Models\Appointment::count();
            $statusRows = [
                'pending'   => ['label' => 'Pending',   'color' => '#EF9F27'],
                'confirmed' => ['label' => 'Confirmed', 'color' => '#378ADD'],
                'completed' => ['label' => 'Completed', 'color' => '#639922'],
                'cancelled' => ['label' => 'Cancelled', 'color' => '#E24B4A'],
            ];
        @endphp
        <div class="admin-grid">
            <div style="background:#fff; border:1px solid #eeeef2; border-radius:14px; padding:22px 24px;">
                <div style="font-size:13px; font-weight:500; color:#1a1a2e; margin-bottom:18px;">Monthly appointments ({{ now()->year }})</div>
                <div style="display:flex; flex-direction:column; gap:12px;">
                    @foreach(array_slice($months, 0, 6) as $i => $month)
                        @php
                            $cnt = \App\Models\Appointment::whereMonth('appointment_date', $i+1)->whereYear('appointment_date', now()->year)->count();
                            $pct = $yearTotal > 0 ? round(($cnt/$yearTotal)*100) : 0;
                        @endphp
                        <div style="display:flex; align-items:center; gap:10px;">
                            <span style="font-size:12px; color:#888780; width:30px; flex-shrink:0;">{{ $month }}</span>
                            <div style="flex:1; height:4px; background:#F1EFE8; border-radius:99px; overflow:hidden; min-width:0;">
                                <div style="width:{{ $pct }}%; height:100%; background:#26215C; border-radius:99px;"></div>
                            </div>
                            <span style="font-size:12px; color:#1a1a2e; font-weight:500; width:20px; text-align:right; flex-shrink:0;">{{ $cnt }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div style="background:#fff; border:1px solid #eeeef2; border-radius:14px; padding:22px 24px;">
                <div style="font-size:13px; font-weight:500; color:#1a1a2e; margin-bottom:18px;">Appointment status</div>
                <div style="display:flex; flex-direction:column; gap:14px;">
                    @foreach($statusRows as $status => $info)
                        @php
                            $cnt = \App\Models\Appointment::where('status',$status)->count();
                            $pct = $allTotal > 0 ? round(($cnt/$allTotal)*100) : 0;
                        @endphp
                        <div>
                            <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:6px;">
                                <span style="color:#888780;">{{ $info['label'] }}</span>
                                <span style="color:#1a1a2e; font-weight:500;">{{ $cnt }}</span>
                            </div>
                            <div style="height:4px; background:#F1EFE8; border-radius:99px; overflow:hidden;">
                                <div style="width:{{ $pct }}%; height:100%; background:{{ $info['color'] }}; border-radius:99px;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- UPCOMING BOOKINGS --}}
    <div style="width:100%;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
            <div style="font-size:14px; font-weight:500; color:#1a1a2e;">Upcoming bookings</div>
            <a href="{{ route('appointments.index') }}" style="font-size:12px; color:#534AB7; text-decoration:none;">View all</a>
        </div>

        @if($upcomingList->isEmpty())
            <div style="background:#fff; border:1px solid #eeeef2; border-radius:14px; padding:56px 24px; text-align:center; width:100%;">
                <div style="font-size:13px; color:#888780;">No upcoming bookings yet.</div>
                <a href="{{ route('appointments.create') }}" style="display:inline-block; margin-top:8px; font-size:13px; color:#534AB7; text-decoration:none;">Book an appointment</a>
            </div>
        @else
            <div style="display:flex; flex-direction:column; gap:8px; width:100%;">
                @foreach($upcomingList as $apt)
                    <div style="background:#fff; border:1px solid #eeeef2; border-radius:14px; padding:16px 20px; width:100%;">
                        <div class="appt-card-inner">

                            {{-- Date block --}}
                            <div style="width:50px; height:50px; background:#EEEDFE; border-radius:10px; display:flex; flex-direction:column; align-items:center; justify-content:center; flex-shrink:0;">
                                <div style="font-size:9px; font-weight:500; color:#534AB7; text-transform:uppercase; letter-spacing:0.04em;">{{ $apt->appointment_date->format('M') }}</div>
                                <div style="font-size:20px; font-weight:500; color:#26215C; line-height:1.1;">{{ $apt->appointment_date->format('d') }}</div>
                            </div>

                            {{-- Info --}}
                            <div style="min-width:0;">
                                <div style="font-size:14px; font-weight:500; color:#1a1a2e; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                    {{ $apt->service->name ?? 'N/A' }}
                                </div>
                                <div style="font-size:12px; color:#888780; margin-top:4px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                    {{ $slotLabels[$apt->time_slot] ?? ucwords(str_replace('_',' ',$apt->time_slot)) }}
                                    &nbsp;·&nbsp;
                                    {{ $apt->hospital->name ?? $apt->preferred_location ?? 'N/A' }}
                                </div>
                                <div style="font-size:11px; color:#888780; margin-top:3px;">
                                    Queue: <span style="color:#26215C; font-weight:500;">{{ $apt->queue_number ?? 'N/A' }}</span>
                                    @if($apt->slot_position) &nbsp;·&nbsp; Position {{ $apt->slot_position }} of 10 @endif
                                </div>
                                {{-- Mobile badges --}}
                                <div class="mobile-badges" style="display:none; gap:6px; margin-top:8px; flex-wrap:wrap;">
                                    <span style="padding:3px 9px; border-radius:99px; font-size:11px; font-weight:500;
                                        {{ $apt->status === 'confirmed' ? 'background:#E6F1FB; color:#0C447C;' : '' }}
                                        {{ $apt->status === 'pending'   ? 'background:#FAEEDA; color:#633806;' : '' }}
                                        {{ $apt->status === 'cancelled' ? 'background:#FCEBEB; color:#791F1F;' : '' }}
                                        {{ $apt->status === 'completed' ? 'background:#EAF3DE; color:#173404;' : '' }}">
                                        {{ ucfirst($apt->status) }}
                                    </span>
                                    <span style="padding:3px 9px; border-radius:99px; font-size:11px; font-weight:500; background:#F1EFE8; color:#444441;">
                                        {{ ucfirst(str_replace('_',' ',$apt->payment_method ?? '')) }} · {{ ucfirst($apt->payment_status ?? 'unpaid') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Badges + Cancel --}}
                            <div class="badge-col">
                                <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:500; white-space:nowrap;
                                    {{ $apt->status === 'confirmed' ? 'background:#E6F1FB; color:#0C447C;' : '' }}
                                    {{ $apt->status === 'pending'   ? 'background:#FAEEDA; color:#633806;' : '' }}
                                    {{ $apt->status === 'cancelled' ? 'background:#FCEBEB; color:#791F1F;' : '' }}
                                    {{ $apt->status === 'completed' ? 'background:#EAF3DE; color:#173404;' : '' }}">
                                    {{ ucfirst($apt->status) }}
                                </span>
                                <span style="padding:3px 10px; border-radius:99px; font-size:11px; font-weight:500; white-space:nowrap;
                                    {{ $apt->payment_status === 'paid'    ? 'background:#EAF3DE; color:#173404;' : '' }}
                                    {{ $apt->payment_status === 'partial' ? 'background:#E6F1FB; color:#0C447C;' : '' }}
                                    {{ !in_array($apt->payment_status ?? '', ['paid','partial']) ? 'background:#F1EFE8; color:#444441;' : '' }}">
                                    {{ ucfirst(str_replace('_',' ',$apt->payment_method ?? '')) }} · {{ ucfirst($apt->payment_status ?? 'unpaid') }}
                                </span>
                                @if($apt->status !== 'cancelled')
                                    <form method="POST" action="{{ route('appointments.cancel', $apt) }}"
                                        onsubmit="return confirm('Cancel this appointment?')">
                                        @csrf @method('DELETE')
                                        <input type="hidden" name="cancellation_reason" value="Cancelled by user">
                                        <button type="submit" style="background:none; border:none; font-size:11px; color:#A32D2D; cursor:pointer; padding:0;"
                                            onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Cancel</button>
                                    </form>
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ADMIN REPORT --}}
    @if(Auth::user()->isAdmin())
        <div style="display:flex; justify-content:flex-end; padding-bottom:8px;">
            <button onclick="window.print()"
                style="padding:9px 20px; font-size:13px; font-weight:500; color:#444441; background:#fff; border:1px solid #eeeef2; border-radius:8px; cursor:pointer;">
                Generate Report
            </button>
        </div>
    @endif

</div>
@endsection