@extends('layouts.master', ['page_title' => 'Dashboard'])
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">

    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center justify-content-between w-100 me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">All Customer Milk Records</h1>
            </div>
        </div>
    </div>

    {{-- Add Record Modal --}}
    <div class="modal fade" id="AddCompany" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ url('submit_milk_record') }}" enctype="multipart/form-data" class="addForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Customer Milk Record</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label>Customer Name:</label>
                                <select class="form-control" name="customer_id" id="customer_select" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" data-price="{{ $customer->price_liter }}">
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label>Date:</label>
                                <input type="date" class="form-control" name="date" required>
                                @error('date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label>Milk Quantity (Liters):</label>
                                <input name="milk_quantity" type="number" step="0.01" class="form-control" required placeholder="Enter Milk Quantity">
                                @error('milk_quantity')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label>Price Per Liter:</label>
                                <input name="price_per_liter" id="price_per_liter" type="number" step="0.01" class="form-control" required placeholder="Enter Price Per Liter">
                                @error('price_per_liter')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

  <section class="card">
    {{-- Date Filter --}}
    <div class="d-flex align-items-center gap-10 my-10 flex-wrap">
        <form method="GET" action="{{ url('customer-milk-records') }}" class="d-flex align-items-center gap-3">
            <label class="fw-bold">Select Date:</label>
            <input type="date" name="date" class="form-control w-auto"
                value="{{ $selectedDate }}" onchange="this.form.submit()">
        </form>
    </div>

    <div class="table-responsive">
        <table class="table text-center">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer Name</th>
                    <th>Milk Quantity (Liters)</th>
                    <th>Price Per Liter</th>
                    <th>Amount</th>
                    <th>Milk Delivered</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $index => $customer)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->liter_per_day }}</td>
                    <td>{{ $customer->price_liter }}</td>
                    <td>
                        @if($customer->liter_per_day && $customer->price_liter)
                            {{ $customer->liter_per_day * $customer->price_liter }}
                        @else
                            0
                        @endif
                    </td>
                    <td>
                        <div class="form-check form-switch d-flex justify-content-center">
                            <input class="form-check-input delivery-toggle" type="checkbox"
                                data-customer-id="{{ $customer->id }}"
                                {{-- check if delivery exists and was delivered for this date --}}
                                {{ isset($existingRecords[$customer->id]) && $existingRecords[$customer->id] ? 'checked' : '' }}>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">No customers found</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Save Button --}}
        @if($customers->count() > 0)
        <div class="d-flex justify-content-end my-3 px-3">
            <button id="save-deliveries-btn" class="btn btn-success">
                <i class="las la-save"></i> Save Deliveries for {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}
            </button>
        </div>
        @endif
    </div>
</section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Auto-fill price on customer select
    document.getElementById('customer_select').addEventListener('change', function () {
        const price = this.options[this.selectedIndex].getAttribute('data-price');
        document.getElementById('price_per_liter').value = price ?? '';
    });

    // Add record modal
    document.querySelectorAll('.make-button').forEach(el => {
        el.addEventListener('click', function () {
            new bootstrap.Modal(document.getElementById('AddCompany')).show();
        });
    });

    // Save all deliveries button
       document.getElementById('save-deliveries-btn')?.addEventListener('click', function () {
        const toggles = document.querySelectorAll('.delivery-toggle');
        const deliveries = [];

        // collect all customers and their delivery status
        toggles.forEach(toggle => {
            deliveries.push({
                customer_id:    toggle.getAttribute('data-customer-id'),
                milk_delivered: toggle.checked ? 1 : 0
            });
        });

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="las la-spinner"></i> Saving...';

fetch('{{ url("save_day_deliveries") }}', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({
        deliveries: deliveries,
        date: '{{ $selectedDate }}'
    })
})
.then(res => {
    console.log('Status Code:', res.status); // 👈 check this in console
    if (!res.ok) {
        return res.text().then(text => { // 👈 get full error message
            console.error('Server Error:', text);
            throw new Error(text);
        });
    }
    return res.json();
})
.then(data => {
    console.log('Response:', data);
    if (data.success) {
        btn.innerHTML = '<i class="las la-check"></i> Saved!';
        btn.classList.replace('btn-success', 'btn-primary');

        setTimeout(() => {
            btn.innerHTML = '<i class="las la-save"></i> Save Deliveries for {{ \Carbon\Carbon::parse($selectedDate)->format("d M Y") }}';
            btn.classList.replace('btn-primary', 'btn-success');
            btn.disabled = false;
        }, 2000);
    }
})
.catch(err => {
    console.error('Full Error:', err); // 👈 now shows real error
    btn.innerHTML = '<i class="las la-times"></i> Failed!';
    btn.classList.replace('btn-success', 'btn-danger');
    btn.disabled = false;
});
    });

});
</script>


@endsection