<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spa Analytics Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2563eb;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }
        .summary {
            margin-bottom: 30px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .summary-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: #f9fafb;
        }
        .summary-card h3 {
            margin: 0 0 10px 0;
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }
        .summary-card p {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 11px;
        }
        table td {
            padding: 8px 10px;
            border: 1px solid #ddd;
            font-size: 11px;
        }
        table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #e5e7eb;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>HeaLife - Spa Analytics Report</h1>
        <p>Period: {{ $start_date }} to {{ $end_date }}</p>
        <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <div class="summary">
        <h2 class="section-title">Executive Summary</h2>
        <div class="summary-grid">
            <div class="summary-card">
                <h3>Total Revenue</h3>
                <p>Rp {{ number_format($total_revenue, 0, ',', '.') }}</p>
            </div>
            <div class="summary-card">
                <h3>Total Bookings</h3>
                <p>{{ number_format($total_bookings) }}</p>
            </div>
            <div class="summary-card">
                <h3>Completed Bookings</h3>
                <p>{{ number_format($completed_bookings) }}</p>
            </div>
            <div class="summary-card">
                <h3>Cancelled Bookings</h3>
                <p>{{ number_format($cancelled_bookings) }}</p>
            </div>
        </div>
    </div>

    <h2 class="section-title">Bookings by Status</h2>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Number of Bookings</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings_by_status as $status)
            <tr>
                <td>{{ ucfirst($status->status) }}</td>
                <td>{{ number_format($status->total) }}</td>
                <td>{{ $total_bookings > 0 ? number_format(($status->total / $total_bookings) * 100, 1) : 0 }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="section-title">Top 10 Services by Revenue</h2>
    <table>
        <thead>
            <tr>
                <th>Service Name</th>
                <th>Total Bookings</th>
                <th>Total Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($top_services as $service)
            <tr>
                <td>{{ $service->service_name }}</td>
                <td>{{ number_format($service->bookings) }}</td>
                <td>Rp {{ number_format($service->revenue, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="section-title">Revenue by Spa Location</h2>
    <table>
        <thead>
            <tr>
                <th>Spa Location</th>
                <th>Total Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($revenue_by_spa as $spa)
            <tr>
                <td>{{ $spa->nama }}</td>
                <td>Rp {{ number_format($spa->total_revenue, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>&copy; {{ date('Y') }} HeaLife. All rights reserved.</p>
        <p>This report is generated automatically by the HeaLife Analytics System.</p>
    </div>
</body>
</html>
