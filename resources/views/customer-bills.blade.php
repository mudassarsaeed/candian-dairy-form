@extends('layouts.master', ['page_title' => 'Customer Bills'])
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">

    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center w-100 me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Customer Monthly Bills</h1>
            </div>
        </div>
    </div>

    {{-- Month Selector --}}
    <section class="card p-4 mb-4">
        <form method="GET" action="{{ url('customer-bills') }}" class="d-flex align-items-center gap-3 flex-wrap">
            <label class="fw-bold">Select Month:</label>
            <input type="month" name="month" class="form-control w-auto"
                value="{{ $selectedMonth }}"
                max="{{ now()->format('Y-m') }}"
                onchange="this.form.submit()">
            <span class="text-muted fw-bold">
                {{ \Carbon\Carbon::parse($selectedMonth . '-01')->format('F Y') }}
            </span>
        </form>
    </section>

    {{-- Summary Cards --}}
    @php
        $totalPaid   = collect($bills)->where('status', 'Paid')->count();
        $totalUnpaid = collect($bills)->where('status', 'Unpaid')->count();
        $totalRev    = collect($bills)->sum('total_amount');
        $paidRev     = collect($bills)->where('status', 'Paid')->sum('total_amount');
    @endphp
    <div class="row mb-4">
        <div class="col-lg-3 col-6 mb-3">
            <div class="card p-3 text-center border-primary">
                <h6 class="text-muted">Total Customers</h6>
                <h3 class="fw-bold text-primary">{{ count($bills) }}</h3>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-3">
            <div class="card p-3 text-center border-success">
                <h6 class="text-muted">Paid</h6>
                <h3 class="fw-bold text-success" id="summary-paid">{{ $totalPaid }}</h3>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-3">
            <div class="card p-3 text-center border-danger">
                <h6 class="text-muted">Unpaid</h6>
                <h3 class="fw-bold text-danger" id="summary-unpaid">{{ $totalUnpaid }}</h3>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-3">
            <div class="card p-3 text-center border-warning">
                <h6 class="text-muted">Total Revenue</h6>
                <h3 class="fw-bold text-warning" id="summary-revenue">Rs. {{ number_format($totalRev, 0) }}</h3>
            </div>
        </div>
    </div>

    {{-- Bills Table --}}
    <section class="card">
        <div class="d-flex align-items-center justify-content-between gap-10 my-10 px-4 flex-wrap">
            <h5 class="fw-bold mb-0">
                Bills for {{ \Carbon\Carbon::parse($selectedMonth . '-01')->format('F Y') }}
            </h5>
        </div>

        <div class="table-responsive">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Type</th>
                        <th>Days Delivered</th>
                        <th>Total Liters</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bills as $id => $bill)
                    @if($bill['total_days'] > 0)
                    <tr id="row-{{ $id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-start">
                            <strong>{{ $bill['customer']->name }}</strong><br>
                            <small class="text-muted">{{ $bill['customer']->phone }}</small>
                        </td>
                        <td>
                            @if($bill['customer']->customer_type == 'Home Delivery')
                                <span class="badge bg-success">Home Delivery</span>
                            @elseif($bill['customer']->customer_type == 'Bulk Delivery')
                                <span class="badge bg-primary">Bulk Delivery</span>
                            @else
                                <span class="badge bg-warning text-dark">Bulk Pick Up</span>
                            @endif
                        </td>
                        <td>{{ $bill['total_days'] }} days</td>
                        <td>{{ $bill['total_liters'] }} L</td>
                        <td><strong>Rs. {{ number_format($bill['total_amount'], 0) }}</strong></td>
                        <td>
                            <span class="badge status-badge-{{ $id }} {{ $bill['status'] == 'Paid' ? 'bg-success' : 'bg-danger' }}">
                                {{ $bill['status'] }}
                            </span>
                        </td>
                        <td>
                            {{-- View Receipt --}}
                            <a href="{{ url('customer-receipt?customer_id='.$id.'&month='.$selectedMonth) }}"
                                class="btn btn-sm btn-outline-primary me-1" target="_blank">
                                <i class="las la-file-invoice"></i> Receipt
                            </a>
                            {{-- Toggle Paid/Unpaid --}}
                            <button class="btn btn-sm {{ $bill['status'] == 'Paid' ? 'btn-warning' : 'btn-success' }} toggle-status-btn me-1"
                                data-customer-id="{{ $id }}"
                                data-month="{{ $selectedMonth }}"
                                data-status="{{ $bill['status'] }}"
                                data-liters="{{ $bill['total_liters'] }}"
                                data-amount="{{ $bill['total_amount'] }}"
                                data-days="{{ $bill['total_days'] }}">
                                {{ $bill['status'] == 'Paid' ? 'Mark Unpaid' : 'Mark Paid' }}
                            </button>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.toggle-status-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const customerId = this.dataset.customerId;
            const newStatus  = this.dataset.status === 'Paid' ? 'Unpaid' : 'Paid';

            fetch('{{ url("update_bill_status") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    customer_id:   customerId,
                    month:         this.dataset.month,
                    status:        newStatus,
                    total_liters:  this.dataset.liters,
                    total_amount:  this.dataset.amount,
                    total_days:    this.dataset.days,
                })
            })
            .then(res => res.json())
           .then(data => {
                if (data.success) {
                    // Update badge
                    const badge = document.querySelector(`.status-badge-${customerId}`);
                    badge.textContent = newStatus;
                    badge.className   = `badge status-badge-${customerId} ${newStatus === 'Paid' ? 'bg-success' : 'bg-danger'}`;

                    // Update button
                    this.dataset.status = newStatus;
                    this.textContent    = newStatus === 'Paid' ? 'Mark Unpaid' : 'Mark Paid';
                    this.className      = `btn btn-sm ${newStatus === 'Paid' ? 'btn-warning' : 'btn-success'} toggle-status-btn me-1`;

                    // ✅ Simple math — just add or subtract 1
                    const paidEl   = document.getElementById('summary-paid');
                    const unpaidEl = document.getElementById('summary-unpaid');

                    let currentPaid   = parseInt(paidEl.textContent);
                    let currentUnpaid = parseInt(unpaidEl.textContent);

                    if (newStatus === 'Paid') {
                        paidEl.textContent   = currentPaid + 1;
                        unpaidEl.textContent = currentUnpaid - 1;
                    } else {
                        paidEl.textContent   = currentPaid - 1;
                        unpaidEl.textContent = currentUnpaid + 1;
                    }
                }
         })
            .catch(err => console.error(err));
        });
    });
});
</script>
@endsection