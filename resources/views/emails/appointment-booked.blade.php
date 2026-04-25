<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f6fb;
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }
        .container {
            max-width: 520px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        }
        .content {
            padding: 48px 40px;
        }
        .branch {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #7C3AED;
            margin-bottom: 16px;
        }
        .date {
            font-size: 32px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        .time {
            font-size: 24px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        .doctor {
            font-size: 18px;
            font-weight: 500;
            color: #6B7280;
            margin-bottom: 32px;
        }
        .confirmed {
            background-color: #ECFDF5;
            color: #059669;
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 24px;
        }
        .message {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 16px;
            letter-spacing: -0.5px;
        }
        .name {
            font-size: 16px;
            color: #4B5563;
            margin-bottom: 32px;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background-color: #111827;
            color: white;
            text-decoration: none;
            border-radius: 40px;
            font-weight: 600;
            font-size: 14px;
            margin-top: 8px;
        }
        .button:hover {
            background-color: #1F2937;
        }
        .divider {
            height: 1px;
            background-color: #F3F4F6;
            margin: 32px 0;
        }
        .details {
            text-align: left;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
        }
        .detail-label {
            color: #9CA3AF;
            font-size: 13px;
        }
        .detail-value {
            color: #111827;
            font-weight: 500;
            font-size: 13px;
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
        @media (max-width: 600px) {
            .content {
                padding: 32px 24px;
            }
            .date {
                font-size: 24px;
            }
            .time {
                font-size: 18px;
            }
            .message {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <div class="branch">{{ strtoupper($appointment->service->name ?? 'BOOKEASE') }}</div>
            <div class="date">{{ $appointment->appointment_date->format('l, F j, Y') }}</div>
            <div class="time">{{ $appointment->appointment_date->format('g:i A') }}</div>
            <div class="doctor">{{ $appointment->service->name }} Session</div>
            
            <div class="confirmed">{{ strtoupper($appointment->status) }}</div>
            
            <div class="message">{{ $appointment->user->name }}, we've got you</div>
            <div class="name">confirmed for your appointment.</div>
            
            <a href="{{ route('appointments.index') }}" class="button">MY APPOINTMENTS</a>
            
            <div class="divider"></div>
            
            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">Duration</span>
                    <span class="detail-value">{{ $appointment->service->duration_minutes }} minutes</span>
                </div>
                @if($appointment->service->price)
                <div class="detail-row">
                    <span class="detail-label">Price</span>
                    <span class="detail-value">${{ number_format($appointment->service->price, 2) }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">Booking ID</span>
                    <span class="detail-value">#{{ $appointment->id }}</span>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} BookEase. All rights reserved.</p>
            <p style="margin-top: 8px; font-size: 11px;">Need to reschedule? <a href="{{ route('appointments.index') }}" style="color: #7C3AED; text-decoration: none;">Manage your appointments</a></p>
        </div>
    </div>
</body>
</html>