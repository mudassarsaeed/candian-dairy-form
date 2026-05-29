<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $customer->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }

        .receipt-wrapper {
            max-width: 600px;
            margin: 30px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .receipt-header {
            background: linear-gradient(135deg, #1a5276, #2980b9);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .receipt-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
            object-fit: cover;
            border: 3px solid rgba(255,255,255,0.5);
        }

        .receipt-header h2 { font-size: 22px; margin-bottom: 4px; }
        .receipt-header p  { font-size: 13px; opacity: 0.85; }

        .receipt-body { padding: 25px 30px; }

        .customer-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .customer-info h4 { color: #1a5276; margin-bottom: 8px; }
        .customer-info p  { font-size: 14px; color: #555; margin: 3px 0; }

        .bill-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .bill-table thead tr {
            background: #1a5276;
            color: white;
        }

        .bill-table th,
        .bill-table td {
            padding: 10px 12px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        .bill-table tbody tr:nth-child(even) { background: #f8f9fa; }
        .bill-table tbody tr:hover { background: #e8f4fc; }

        .bill-summary {
            background: #1a5276;
            color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .bill-summary .row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 14px;
        }

        .bill-summary .total-row {
            border-top: 1px solid rgba(255,255,255,0.3);
            margin-top: 10px;
            padding-top: 10px;
            font-size: 18px;
            font-weight: bold;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .status-paid   { background: #d4edda; color: #155724; }
        .status-unpaid { background: #f8d7da; color: #721c24; }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-print    { background: #1a5276; color: white; }
        .btn-pdf      { background: #c0392b; color: white; }
        .btn-whatsapp { background: #25d366; color: white; }
        .btn-back     { background: #95a5a6; color: white; }

        .receipt-footer {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            color: #888;
            font-size: 12px;
            border-top: 1px solid #eee;
        }

        @media print {
            body { background: white; }
            .receipt-wrapper { box-shadow: none; margin: 0; border-radius: 0; }
            .action-buttons { display: none; }
            .receipt-footer { display: none; }
        }
    </style>
</head>
<body>

<div class="receipt-wrapper" id="receipt">

    {{-- Header --}}
    <div class="receipt-header">
        <img src="{{ asset('assets/images/mainlogo.png') }}" alt="Farm Logo"
            onerror="this.style.display='none'">
        <h2>Canadian Dairy Farm</h2>
        <p>Monthly Milk Receipt</p>
        <p>{{ \Carbon\Carbon::parse($selectedMonth . '-01')->format('F Y') }}</p>
    </div>

    {{-- Body --}}
    <div class="receipt-body">

        {{-- Action Buttons --}}
        <div class="action-buttons">
            <button class="btn btn-print" onclick="window.print()">
                🖨️ Print
            </button>
            <button class="btn btn-pdf" onclick="downloadPDF()">
                📄 Download PDF
            </button>
            <a class="btn btn-whatsapp" id="whatsapp-btn" href="#" target="_blank">
                💬 Share on WhatsApp
            </a>
            <a class="btn btn-back" href="{{ url('customer-bills?month='.$selectedMonth) }}">
                ← Back
            </a>
        </div>

        {{-- Customer Info --}}
        <div class="customer-info">
            <h4>{{ $customer->name }}</h4>
            <p>📍 {{ $customer->address ?? 'N/A' }}</p>
            <p>📞 {{ $customer->phone ?? 'N/A' }}</p>
            @if($customer->whatsapp)
                <p>💬 {{ $customer->whatsapp }}</p>
            @endif
            <p>
                Type: <strong>{{ $customer->customer_type }}</strong> &nbsp;|&nbsp;
                Status:
                <span class="status-badge {{ $bill && $bill->status == 'Paid' ? 'status-paid' : 'status-unpaid' }}">
                    {{ $bill ? $bill->status : 'Unpaid' }}
                </span>
            </p>
        </div>

        {{-- Daily Records Table --}}
        @if($records->count() > 0)
        <table class="bill-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Liters</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $index => $record)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->date)->format('d M Y') }}</td>
                    <td>{{ $record->day_liter }} L</td>
                    <td>Rs. {{ number_format($record->price_day_liter, 0) }}</td>
                    <td>Rs. {{ number_format($record->day_liter * $record->price_day_liter, 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p style="text-align:center; color:#888; padding:20px;">No delivery records for this month.</p>
        @endif

        {{-- Bill Summary --}}
        <div class="bill-summary">
            <div class="row">
                <span>Total Days Delivered</span>
                <span>{{ $totalDays }} days</span>
            </div>
            <div class="row">
                <span>Total Milk</span>
                <span>{{ $totalLiters }} L</span>
            </div>
            <div class="row">
                <span>Rate Per Liter</span>
                <span>Rs. {{ number_format($customer->price_liter, 0) }}/L</span>
            </div>
            <div class="row total-row">
                <span>Total Bill</span>
                <span>Rs. {{ number_format($totalAmount, 0) }}</span>
            </div>
        </div>

    </div>

    {{-- Footer --}}
    <div class="receipt-footer">
        Generated on {{ now()->format('d M Y, h:i A') }} &nbsp;|&nbsp; Canadian Dairy Farm
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    // WhatsApp message
    const customerName  = "{{ $customer->name }}";
    const month         = "{{ \Carbon\Carbon::parse($selectedMonth . '-01')->format('F Y') }}";
    const totalDays     = "{{ $totalDays }}";
    const totalLiters   = "{{ $totalLiters }}";
    const totalAmount   = "{{ number_format($totalAmount, 0) }}";
    const status        = "{{ $bill ? $bill->status : 'Unpaid' }}";
    const whatsapp      = "{{ $customer->whatsapp ?? $customer->phone ?? '' }}";

    const message = `*Canadian Dairy Farm*\n` +
        `================================\n` +
        `Customer: ${customerName}\n` +
        `Month: ${month}\n` +
        `--------------------------------\n` +
        `Days Delivered: ${totalDays} days\n` +
        `Total Milk: ${totalLiters} L\n` +
        `Total Bill: Rs. ${totalAmount}\n` +
        `Status: ${status}\n` +
        `================================\n` +
        `Thank you for your business!`;

    const phoneNumber = whatsapp.replace(/[^0-9]/g, '');
    const waLink = phoneNumber
        ? `https://wa.me/92${phoneNumber.replace(/^0/, '')}?text=${encodeURIComponent(message)}`
        : `https://wa.me/?text=${encodeURIComponent(message)}`;

    document.getElementById('whatsapp-btn').href = waLink;

    // PDF Download
    function downloadPDF() {
        const element = document.getElementById('receipt');
        const buttons = document.querySelector('.action-buttons');
        buttons.style.display = 'none';

        html2pdf()
            .set({
                margin:      10,
                filename:    `Receipt-${customerName}-${month}.pdf`,
                image:       { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF:       { unit: 'mm', format: 'a4', orientation: 'portrait' }
            })
            .from(element)
            .save()
            .then(() => {
                buttons.style.display = 'flex';
            });
    }
</script>

</body>
</html>