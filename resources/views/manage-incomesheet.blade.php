@extends('layouts.master', ['page_title' => 'Income Sheet'])
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">

    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center justify-content-between w-100 me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Income Sheet</h1>
            </div>
        </div>
    </div>

    <section class="card p-4 mb-4">
        {{-- Date Range Filter --}}
        <form method="GET" action="{{ url('income-sheet/report') }}">
            <div class="d-flex align-items-end gap-3 flex-wrap">
                <div>
                    <label class="fw-bold">From Date:</label>
                    <input type="date" name="from_date" class="form-control"
                        value="{{ $fromDate ?? '' }}" required>
                </div>
                <div>
                    <label class="fw-bold">To Date:</label>
                    <input type="date" name="to_date" class="form-control"
                        value="{{ $toDate ?? '' }}" required>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="las la-search"></i> Show Report
                    </button>
                </div>
                {{-- Quick filters --}}
                <div class="d-flex gap-2 ms-auto">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setThisMonth()">This Month</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setLastMonth()">Last Month</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setThisYear()">This Year</button>
                </div>
            </div>
        </form>
    </section>

    @isset($salesRecords)

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-lg-4">
            <div class="card p-4 text-center border-success">
                <h6 class="text-muted">Total Sales</h6>
                <h3 class="text-success fw-bold">Rs. {{ number_format($totalSales, 2) }}</h3>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card p-4 text-center border-danger">
                <h6 class="text-muted">Total Expenses</h6>
                <h3 class="text-danger fw-bold">Rs. {{ number_format($totalExpenses, 2) }}</h3>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card p-4 text-center {{ $netIncome >= 0 ? 'border-primary' : 'border-warning' }}">
                <h6 class="text-muted">Net Income</h6>
                <h3 class="fw-bold {{ $netIncome >= 0 ? 'text-primary' : 'text-warning' }}">
                    Rs. {{ number_format($netIncome, 2) }}
                </h3>
            </div>
        </div>
    </div>

    {{-- Sales Table --}}
    <section class="card p-4 mb-4">
        <h5 class="fw-bold mb-3">
            Sales Report
            <span class="text-muted fs-6">
                ({{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }} — {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }})
            </span>
        </h5>
        <div class="table-responsive">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Milk (Liters)</th>
                        <th>Price/Liter</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salesRecords as $index => $record)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $record->customer->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($record->date)->format('d M Y') }}</td>
                        <td>{{ $record->day_liter }}</td>
                        <td>Rs. {{ number_format($record->price_day_liter, 2) }}</td>
                        <td>Rs. {{ number_format($record->day_liter * $record->price_day_liter, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-muted">No sales records found for this period</td>
                    </tr>
                    @endforelse
                </tbody>
                @if($salesRecords->count() > 0)
                <tfoot>
                    <tr class="fw-bold">
                        <td colspan="5" class="text-end">Total Sales:</td>
                        <td class="text-success">Rs. {{ number_format($totalSales, 2) }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </section>

    {{-- Expenses Table --}}
    <section class="card p-4 mb-4">
        <h5 class="fw-bold mb-3">
            Expenses Report
            <span class="text-muted fs-6">
                ({{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }} — {{ \Carbon\Carbon::parse($toDate)->format('d M Y') }})
            </span>
        </h5>
        <div class="table-responsive">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order Date</th>
                        <th>Unit</th>
                        <th>Bags/Bails</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenseRecords as $index => $expense)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($expense->order_date)->format('d M Y') }}</td>
                        <td>{{ $expense->unit ?? 'N/A' }}</td>
                        <td>{{ $expense->number_bags_bails ?? 'N/A' }}</td>
                        <td>Rs. {{ number_format($expense->unit_price, 2) }}</td>
                        <td>Rs. {{ number_format($expense->total, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-muted">No expense records found for this period</td>
                    </tr>
                    @endforelse
                </tbody>
                @if($expenseRecords->count() > 0)
                <tfoot>
                    <tr class="fw-bold">
                        <td colspan="5" class="text-end">Total Expenses:</td>
                        <td class="text-danger">Rs. {{ number_format($totalExpenses, 2) }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </section>

    @endisset

</div>

<script>
    function setThisMonth() {
        const now = new Date();
        const from = new Date(now.getFullYear(), now.getMonth(), 1);
        const to   = new Date(now.getFullYear(), now.getMonth() + 1, 0);
        document.querySelector('[name=from_date]').value = formatDate(from);
        document.querySelector('[name=to_date]').value   = formatDate(to);
    }

    function setLastMonth() {
        const now  = new Date();
        const from = new Date(now.getFullYear(), now.getMonth() - 1, 1);
        const to   = new Date(now.getFullYear(), now.getMonth(), 0);
        document.querySelector('[name=from_date]').value = formatDate(from);
        document.querySelector('[name=to_date]').value   = formatDate(to);
    }

    function setThisYear() {
        const year = new Date().getFullYear();
        document.querySelector('[name=from_date]').value = `${year}-01-01`;
        document.querySelector('[name=to_date]').value   = `${year}-12-31`;
    }

    function formatDate(date) {
        return date.toISOString().split('T')[0];
    }
</script>

@endsection