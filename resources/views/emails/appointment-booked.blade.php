<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            line-height: 1.5;
        }

        .container {
            max-width: 560px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 40px 30px;
            text-align: center;
        }

        .logo {
            font-size: 32px;
            font-weight: 800;
            color: white;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .logo span {
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 12px;
            border-radius: 40px;
            font-size: 12px;
            margin-left: 8px;
        }

        .check-icon {
            width: 64px;
            height: 64px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .check-icon svg {
            width: 32px;
            height: 32px;
            color: #10b981;
        }

        .content {
            padding: 40px 40px 30px;
        }

        .greeting {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
            letter-spacing: -0.3px;
        }

        .greeting span {
            color: #667eea;
        }

        .subtitle {
            color: #6B7280;
            font-size: 14px;
            margin-bottom: 32px;
        }

        .appointment-card {
            background: #F9FAFB;
            border-radius: 20px;
            padding: 24px;
            margin: 24px 0;
            border: 1px solid #E5E7EB;
        }

        .appointment-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #E5E7EB;
        }

        .appointment-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .icon {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .icon svg {
            width: 20px;
            height: 20px;
        }

        .info {
            flex: 1;
        }

        .info-label {
            font-size: 11px;
            color: #9CA3AF;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
            margin-top: 4px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            background: #FEF3C7;
            color: #D97706;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 24px;
        }

        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 32px;
            flex-wrap: wrap;
        }

        .btn-primary {
            flex: 1;
            display: inline-block;
            padding: 14px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            flex: 1;
            display: inline-block;
            padding: 14px 24px;
            background: white;
            color: #6B7280;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
            border: 1px solid #E5E7EB;
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            border-color: #EF4444;
            color: #EF4444;
        }

        .btn-outline {
            flex: 1;
            display: inline-block;
            padding: 14px 24px;
            background: white;
            color: #10B981;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
            border: 1px solid #10B981;
            transition: all 0.2s;
        }

        .btn-outline:hover {
            background: #10B981;
            color: white;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding: 8px 0;
        }

        .detail-row:last-child {
            margin-bottom: 0;
        }

        .detail-label {
            font-size: 13px;
            color: #6B7280;
            font-weight: 500;
        }

        .detail-value {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }

        .footer {
            background-color: #F9FAFB;
            padding: 24px 40px;
            text-align: center;
            border-top: 1px solid #E5E7EB;
        }

        .footer p {
            color: #9CA3AF;
            font-size: 12px;
            margin: 0;
        }

        .footer a {
            color: #667eea;
            text-decoration: none;
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .content {
                padding: 24px 20px;
            }

            .greeting {
                font-size: 20px;
            }

            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="check-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="logo">
                BookEase <span>✓ CONFIRMED</span>
            </div>
        </div>

        <div class="content">
            <div class="greeting">
                Hello, <span>{{ $appointment->user->name }}</span>
            </div>
            <div class="subtitle">Your appointment has been successfully scheduled</div>

            <div class="status-badge">{{ strtoupper($appointment->status) }}</div>

            <div class="appointment-card">
                <div class="appointment-row">
                    <div class="icon">
                        <svg fill="none" stroke="#667eea" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="info">
                        <div class="info-label">Hospital</div>
                        <div class="info-value">
                            {{ $appointment->hospital->name ?? $appointment->preferred_location ?? 'BookEase Partner' }}
                        </div>
                    </div>
                </div>

                <div class="appointment-row">
                    <div class="icon">
                        <svg fill="none" stroke="#667eea" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 11a2 2 0 100-4 2 2 0 000 4z" />
                        </svg>
                    </div>
                    <div class="info">
                        <div class="info-label">Location</div>
                        <div class="info-value">
                            {{ $appointment->hospital->address ?? $appointment->location_address ?? 'Address will be sent separately' }}
                        </div>
                    </div>
                </div>

                <div class="appointment-row">
                    <div class="icon">
                        <svg fill="none" stroke="#667eea" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="info">
                        <div class="info-label">Date & Time</div>
                        <div class="info-value">{{ $appointment->appointment_date->format('l, F j, Y') }} at
                            {{ $appointment->appointment_date->format('g:i A') }}</div>
                    </div>
                </div>

                <div class="appointment-row">
                    <div class="icon">
                        <svg fill="none" stroke="#667eea" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="info">
                        <div class="info-label">Service</div>
                        <div class="info-value">{{ $appointment->service->name }}
                            ({{ $appointment->service->duration_minutes }} min)</div>
                    </div>
                </div>

                @if($appointment->service->price)
                    <!-- Updated price display with the new detail-row format -->
                    <div class="detail-row">
                        <span class="detail-label">Price</span>
                        <span class="detail-value">₱{{ number_format($appointment->service->price, 2) }}</span>
                    </div>
                @endif
            </div>

            <div class="button-group">
                <a href="{{ route('appointments.index') }}" class="btn-primary">📋 MY APPOINTMENTS</a>
                <a href="{{ route('appointments.complete', $appointment->id) }}" class="btn-outline">✓ COMPLETED</a>
                <a href="{{ route('appointments.cancel-page', $appointment->id) }}" class="btn-secondary">✕ CANCEL</a>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} BookEase. All rights reserved.</p>
            <p style="margin-top: 8px;">Need help? <a href="#">Contact Support</a></p>
        </div>
    </div>
</body>

</html>