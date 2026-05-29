@extends('layouts.master', ['page_title' => 'Milk Report'])
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">

    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center w-100 me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Milk Report</h1>
            </div>
        </div>
    </div>

    {{-- Date Selector --}}
    <section class="card p-4 mb-4">
        <form method="GET" action="{{ url('milk-report') }}" class="d-flex align-items-center gap-3">
            <label class="fw-bold">Select Date:</label>
            <input type="date" name="date" class="form-control w-auto"
                value="{{ $selectedDate }}"
                max="{{ now()->format('Y-m-d') }}"
                onchange="this.form.submit()">
            <span class="text-muted">{{ \Carbon\Carbon::parse($selectedDate)->format('l, d M Y') }}</span>
        </form>
    </section>

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-lg-3 col-6 mb-3">
            <div class="card p-3 text-center border-primary">
                <h6 class="text-muted">Total Produced</h6>
                <h3 class="fw-bold text-primary" id="card-total-produced">{{ $totalProduced }} L</h3>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-3">
            <div class="card p-3 text-center border-success">
                <h6 class="text-muted">Total Sold</h6>
                <h3 class="fw-bold text-success" id="card-total-sold">{{ $totalSold }} L</h3>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-3">
            <div class="card p-3 text-center border-warning">
                <h6 class="text-muted">Total Consumed</h6>
                <h3 class="fw-bold text-warning" id="card-total-consumed">{{ $totalConsumed }} L</h3>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-3">
            <div class="card p-3 text-center {{ $netRemaining >= 0 ? 'border-info' : 'border-danger' }}">
                <h6 class="text-muted">Net Remaining</h6>
                <h3 class="fw-bold {{ $netRemaining >= 0 ? 'text-info' : 'text-danger' }}" id="card-net-remaining">{{ $netRemaining }} L</h3>
            </div>
        </div>
    </div>

    <div class="row">

        {{-- LEFT: Animal Milk Input --}}
        <div class="col-lg-5 mb-4">
            <div class="card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Animal Milk Input</h5>
                    <span class="badge bg-primary fs-6">
                        Total: <span id="animal-total">{{ $totalProduced }}</span> L
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm text-center">
                        <thead>
                            <tr>
                                <th>Tag ID</th>
                                <th>Type</th>
                                <th>Milk (L)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($animals as $animal)
                            <tr>
                                <td><strong>{{ $animal->tag_id }}</strong></td>
                                <td>{{ $animal->animal_type }}</td>
                                <td>
                                    <input type="number"
                                        class="form-control form-control-sm text-center animal-milk-input"
                                        style="width: 80px; margin: auto;"
                                        step="0.5" min="0"
                                        data-animal-id="{{ $animal->id }}"
                                        value="{{ isset($animalRecords[$animal->id]) ? $animalRecords[$animal->id]->milk_quantity : 0 }}">
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-muted">No animals found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($animals->count() > 0)
                <div class="d-flex justify-content-end mt-3">
                    <button id="save-animal-milk-btn" class="btn btn-primary btn-sm">
                        <i class="las la-save"></i> Save Animal Milk
                    </button>
                </div>
                @endif
            </div>
        </div>

        {{-- RIGHT: Sales + Consumption --}}
        <div class="col-lg-7 mb-4">

            {{-- Home Delivery --}}
            <div class="card p-4 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0"><span class="badge bg-success me-2">Home Delivery</span></h6>
                    <span class="text-success fw-bold">{{ $totalHomeDelivery }} L — Rs. {{ number_format($homeDeliveryRevenue, 0) }}</span>
                </div>
                @if($homeDelivery->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm text-center">
                        <thead>
                            <tr><th>Customer</th><th>Liters</th><th>Price</th><th>Amount</th></tr>
                        </thead>
                        <tbody>
                            @foreach($homeDelivery as $r)
                            <tr>
                                <td>{{ $r->customer->name ?? 'N/A' }}</td>
                                <td>{{ $r->day_liter }}</td>
                                <td>{{ $r->price_day_liter }}</td>
                                <td>Rs. {{ number_format($r->day_liter * $r->price_day_liter, 0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <p class="text-muted mb-0">No home delivery records for this date.</p>
                @endif
            </div>

            {{-- Bulk Delivery --}}
            <div class="card p-4 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0"><span class="badge bg-primary me-2">Bulk Delivery</span></h6>
                    <span class="text-primary fw-bold">{{ $totalBulkDelivery }} L — Rs. {{ number_format($bulkDeliveryRevenue, 0) }}</span>
                </div>
                @if($bulkDelivery->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm text-center">
                        <thead>
                            <tr><th>Customer</th><th>Liters</th><th>Price</th><th>Amount</th></tr>
                        </thead>
                        <tbody>
                            @foreach($bulkDelivery as $r)
                            <tr>
                                <td>{{ $r->customer->name ?? 'N/A' }}</td>
                                <td>{{ $r->day_liter }}</td>
                                <td>{{ $r->price_day_liter }}</td>
                                <td>Rs. {{ number_format($r->day_liter * $r->price_day_liter, 0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <p class="text-muted mb-0">No bulk delivery records for this date.</p>
                @endif
            </div>

            {{-- Bulk Pickup --}}
            <div class="card p-4 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0"><span class="badge bg-warning text-dark me-2">Bulk Pick Up</span></h6>
                    <span class="text-warning fw-bold">{{ $totalBulkPickup }} L — Rs. {{ number_format($bulkPickupRevenue, 0) }}</span>
                </div>
                @if($bulkPickup->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm text-center">
                        <thead>
                            <tr><th>Customer</th><th>Liters</th><th>Price</th><th>Amount</th></tr>
                        </thead>
                        <tbody>
                            @foreach($bulkPickup as $r)
                            <tr>
                                <td>{{ $r->customer->name ?? 'N/A' }}</td>
                                <td>{{ $r->day_liter }}</td>
                                <td>{{ $r->price_day_liter }}</td>
                                <td>Rs. {{ number_format($r->day_liter * $r->price_day_liter, 0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <p class="text-muted mb-0">No bulk pickup records for this date.</p>
                @endif
            </div>

            {{-- Farm Use / Samples / Waste --}}
            <div class="card p-4">
                <h6 class="fw-bold mb-3">Farm Consumption</h6>
                <div class="row">
                    <div class="col-4">
                        <label class="text-muted">Farm Use (L)</label>
                        <input type="number" id="farm-use" class="form-control form-control-sm text-center consumption-input"
                            step="0.5" min="0" value="{{ $farmUse }}">
                    </div>
                    <div class="col-4">
                        <label class="text-muted">Samples (L)</label>
                        <input type="number" id="samples" class="form-control form-control-sm text-center consumption-input"
                            step="0.5" min="0" value="{{ $samples }}">
                    </div>
                    <div class="col-4">
                        <label class="text-muted">Waste (L)</label>
                        <input type="number" id="waste" class="form-control form-control-sm text-center consumption-input"
                            step="0.5" min="0" value="{{ $waste }}">
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button id="save-summary-btn" class="btn btn-warning btn-sm text-dark">
                        <i class="las la-save"></i> Save Consumption
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- Revenue Summary --}}
    <div class="card p-4 mb-4">
        <h5 class="fw-bold mb-3">Daily Revenue Summary</h5>
        <div class="row text-center">
            <div class="col-lg-3 col-6 mb-3">
                <div class="p-3 bg-light rounded">
                    <div class="text-muted">Home Delivery</div>
                    <div class="fw-bold text-success fs-5">Rs. {{ number_format($homeDeliveryRevenue, 0) }}</div>
                </div>
            </div>
            <div class="col-lg-3 col-6 mb-3">
                <div class="p-3 bg-light rounded">
                    <div class="text-muted">Bulk Delivery</div>
                    <div class="fw-bold text-primary fs-5">Rs. {{ number_format($bulkDeliveryRevenue, 0) }}</div>
                </div>
            </div>
            <div class="col-lg-3 col-6 mb-3">
                <div class="p-3 bg-light rounded">
                    <div class="text-muted">Bulk Pick Up</div>
                    <div class="fw-bold text-warning fs-5">Rs. {{ number_format($bulkPickupRevenue, 0) }}</div>
                </div>
            </div>
            <div class="col-lg-3 col-6 mb-3">
                <div class="p-3 bg-light rounded border border-success">
                    <div class="text-muted">Total Revenue</div>
                    <div class="fw-bold text-success fs-4">Rs. {{ number_format($totalRevenue, 0) }}</div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Auto-update total when animal milk input changes
    document.querySelectorAll('.animal-milk-input').forEach(input => {
        input.addEventListener('input', function () {
            let total = 0;
            document.querySelectorAll('.animal-milk-input').forEach(i => {
                total += parseFloat(i.value) || 0;
            });
            document.getElementById('animal-total').textContent = total.toFixed(2);
            document.getElementById('card-total-produced').textContent = total.toFixed(2) + ' L';
        });
    });

    // Save animal milk records
    document.getElementById('save-animal-milk-btn')?.addEventListener('click', function () {
        const records = [];
        document.querySelectorAll('.animal-milk-input').forEach(input => {
            records.push({
                animal_id:     input.getAttribute('data-animal-id'),
                milk_quantity: parseFloat(input.value) || 0
            });
        });

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="las la-spinner"></i> Saving...';

        fetch('{{ url("save_animal_milk") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                records: records,
                date: '{{ $selectedDate }}'
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                btn.innerHTML = '<i class="las la-check"></i> Saved!';
                btn.classList.replace('btn-primary', 'btn-success');
                setTimeout(() => {
                    btn.innerHTML = '<i class="las la-save"></i> Save Animal Milk';
                    btn.classList.replace('btn-success', 'btn-primary');
                    btn.disabled = false;
                }, 2000);
            }
        })
        .catch(err => {
            console.error(err);
            btn.innerHTML = '<i class="las la-times"></i> Failed!';
            btn.classList.replace('btn-primary', 'btn-danger');
            btn.disabled = false;
        });
    });

    // Save daily consumption summary
    document.getElementById('save-summary-btn')?.addEventListener('click', function () {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="las la-spinner"></i> Saving...';

        fetch('{{ url("save_daily_summary") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                date:     '{{ $selectedDate }}',
                farm_use: parseFloat(document.getElementById('farm-use').value) || 0,
                samples:  parseFloat(document.getElementById('samples').value)  || 0,
                waste:    parseFloat(document.getElementById('waste').value)    || 0,
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                btn.innerHTML = '<i class="las la-check"></i> Saved!';
                btn.classList.replace('btn-warning', 'btn-success');

                // Update Total Consumed card
                const farmUse  = parseFloat(document.getElementById('farm-use').value) || 0;
                const samples  = parseFloat(document.getElementById('samples').value)  || 0;
                const waste    = parseFloat(document.getElementById('waste').value)    || 0;
                const totalConsumed = farmUse + samples + waste;

                document.getElementById('card-total-consumed').textContent = totalConsumed.toFixed(2) + ' L';

                // Update Net Remaining card
                const totalProduced  = parseFloat(document.getElementById('card-total-produced').textContent) || 0;
                const totalSold      = parseFloat(document.getElementById('card-total-sold').textContent)     || 0;
                const netRemaining   = totalProduced - totalSold - totalConsumed;

                const netCard = document.getElementById('card-net-remaining');
                netCard.textContent = netRemaining.toFixed(2) + ' L';
                netCard.className   = netRemaining >= 0 ? 'fw-bold text-info' : 'fw-bold text-danger';

                setTimeout(() => {
                    btn.innerHTML = '<i class="las la-save"></i> Save Consumption';
                    btn.classList.replace('btn-success', 'btn-warning');
                    btn.disabled = false;
                }, 2000);
            }
        })
        .catch(err => {
            console.error(err);
            btn.innerHTML = '<i class="las la-times"></i> Failed!';
            btn.disabled = false;
        });
    });

});
</script>
@endsection