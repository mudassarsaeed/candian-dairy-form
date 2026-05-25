@extends('layouts.master', ['page_title' => 'Manage Calfs'])
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">

    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center w-100 me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Manage Calfs</h1>
            </div>
        </div>
    </div>

    {{-- Add Calf Modal --}}
    <div class="modal fade" id="addCalfModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" action="{{ url('add_calf') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Calf</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label>Tag Number: <span class="text-danger">*</span></label>
                                <input type="text" name="tag_number" class="form-control" required placeholder="e.g. C001">
                                @error('tag_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label>Gender: <span class="text-danger">*</span></label>
                                <select name="gender" class="form-control" required>
                                    <option value="Female">Female</option>
                                    <option value="Male">Male</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label>Calf Date:</label>
                                <input type="date" name="calf_date" class="form-control">
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label>Expected Insemination Date:</label>
                                <input type="date" name="expected_insemination_date" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label>Expected Delivery Date:</label>
                                <input type="date" name="expected_delivery_date" class="form-control">
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label>Notes:</label>
                                <input type="text" name="notes" class="form-control" placeholder="Any additional notes">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Calf</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Calf Modal --}}
    <div class="modal fade" id="editCalfModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" action="{{ url('update_calf') }}">
                @csrf
                <input type="hidden" name="calf_id" id="edit_calf_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Calf</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label>Tag Number:</label>
                                <input type="text" name="tag_number" id="edit_tag_number" class="form-control" required>
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label>Gender:</label>
                                <select name="gender" id="edit_gender" class="form-control" required>
                                    <option value="Female">Female</option>
                                    <option value="Male">Male</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label>Calf Date:</label>
                                <input type="date" name="calf_date" id="edit_calf_date" class="form-control">
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label>Expected Insemination Date:</label>
                                <input type="date" name="expected_insemination_date" id="edit_expected_insemination_date" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label>Expected Delivery Date:</label>
                                <input type="date" name="expected_delivery_date" id="edit_expected_delivery_date" class="form-control">
                            </div>
                            <div class="mb-3 col-lg-6">
                                <label>Notes:</label>
                                <input type="text" name="notes" id="edit_notes" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Calf</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteCalfModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <form action="{{ url('delete_calf') }}" method="POST">
                        @csrf
                        <input type="hidden" name="calf_id" id="delete_calf_id">
                        <h4>Delete Calf?</h4>
                        <p class="text-muted">This action cannot be undone.</p>
                        <div class="d-flex justify-content-center gap-3 mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Calfs Table --}}
    <section class="card">
        <div class="d-flex align-items-center justify-content-between gap-10 my-10 px-4 flex-wrap">
            <h5 class="fw-bold mb-0">All Calfs ({{ $calfs->count() }})</h5>
            <button class="btn btn-primary" id="add-calf-btn">
                <i class="las la-plus"></i> Add Calf
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success mx-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mx-4">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tag Number</th>
                        <th>Gender</th>
                        <th>Calf Date</th>
                        <th>Expected Insemination Date</th>
                        <th>Expected Delivery Date</th>
                        <th>Notes</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($calfs as $index => $calf)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $calf->tag_number }}</strong></td>
                        <td>
                            @if($calf->gender == 'Female')
                                <span class="badge bg-danger">Female</span>
                            @else
                                <span class="badge bg-primary">Male</span>
                            @endif
                        </td>
                        <td>{{ $calf->calf_date ? $calf->calf_date->format('d M Y') : '-' }}</td>
                        <td>{{ $calf->expected_insemination_date ? $calf->expected_insemination_date->format('d M Y') : '-' }}</td>
                        <td>{{ $calf->expected_delivery_date ? $calf->expected_delivery_date->format('d M Y') : '-' }}</td>
                        <td>{{ $calf->notes ?? '-' }}</td>
                        <td>
                            <a href="#" class="text-primary action-button edit-calf-btn"
                                data-id="{{ $calf->id }}"
                                data-tag="{{ $calf->tag_number }}"
                                data-gender="{{ $calf->gender }}"
                                data-calfdate="{{ $calf->calf_date?->format('Y-m-d') }}"
                                data-insemination="{{ $calf->expected_insemination_date?->format('Y-m-d') }}"
                                data-delivery="{{ $calf->expected_delivery_date?->format('Y-m-d') }}"
                                data-notes="{{ $calf->notes }}">
                                <i class="las la-edit"></i>
                            </a>
                            <a href="#" class="text-danger action-button delete-calf-btn ms-2"
                                data-id="{{ $calf->id }}">
                                <i class="las la-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-muted text-center">No calfs found. Add your first calf.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Add calf button
    document.getElementById('add-calf-btn').addEventListener('click', function () {
        new bootstrap.Modal(document.getElementById('addCalfModal')).show();
    });

    // Edit calf button
    document.querySelectorAll('.edit-calf-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('edit_calf_id').value                      = this.dataset.id;
            document.getElementById('edit_tag_number').value                   = this.dataset.tag;
            document.getElementById('edit_gender').value                       = this.dataset.gender;
            document.getElementById('edit_calf_date').value                    = this.dataset.calfdate;
            document.getElementById('edit_expected_insemination_date').value   = this.dataset.insemination;
            document.getElementById('edit_expected_delivery_date').value       = this.dataset.delivery;
            document.getElementById('edit_notes').value                        = this.dataset.notes;

            new bootstrap.Modal(document.getElementById('editCalfModal')).show();
        });
    });

    // Delete calf button
    document.querySelectorAll('.delete-calf-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('delete_calf_id').value = this.dataset.id;
            new bootstrap.Modal(document.getElementById('deleteCalfModal')).show();
        });
    });

});
</script>

@endsection