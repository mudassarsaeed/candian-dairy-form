@extends('layouts.master', ['page_title' => 'Manage Animals'])
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">

    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center justify-content-between w-100 me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Manage Animals</h1>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card p-3 text-center border-primary">
                <h6 class="text-muted">Total Cows</h6>
                <h3 class="fw-bold text-primary">{{ $totalCows }}</h3>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card p-3 text-center border-info">
                <h6 class="text-muted">Heifers</h6>
                <h3 class="fw-bold text-info">{{ $totalHeifers }}</h3>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card p-3 text-center border-warning">
                <h6 class="text-muted">Calves</h6>
                <h3 class="fw-bold text-warning">{{ $totalCalves }}</h3>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card p-3 text-center border-success">
                <h6 class="text-muted">Milking</h6>
                <h3 class="fw-bold text-success">{{ $milking }}</h3>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card p-3 text-center border-danger">
                <h6 class="text-muted">Pregnant</h6>
                <h3 class="fw-bold text-danger">{{ $pregnant }}</h3>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="card p-3 text-center border-secondary">
                <h6 class="text-muted">Dry</h6>
                <h3 class="fw-bold text-secondary">{{ $dry }}</h3>
            </div>
        </div>
    </div>

    {{-- Add Animal Modal --}}
    <div class="modal fade" id="addAnimalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" action="{{ url('add_animal') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Animal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-lg-4">
                                <label>Tag ID: <span class="text-danger">*</span></label>
                                <input type="text" name="tag_id" class="form-control" required placeholder="e.g. ND1, O46">
                                @error('tag_id')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3 col-lg-4">
                                <label>Animal Type: <span class="text-danger">*</span></label>
                                <select name="animal_type" class="form-control" required>
                                    <option value="">Select Type</option>
                                    <option value="Cow">Cow</option>
                                    <option value="Heifer">Heifer</option>
                                    <option value="Calf">Calf</option>
                                </select>
                                @error('animal_type')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3 col-lg-4">
                                <label>Gender: <span class="text-danger">*</span></label>
                                <select name="gender" class="form-control" required>
                                    <option value="Female">Female</option>
                                    <option value="Male">Male</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-lg-4">
                                <label>Status: <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="">Select Status</option>
                                    <option value="Milking">Milking</option>
                                    <option value="Pregnant">Pregnant</option>
                                    <option value="Dry">Dry</option>
                                    <option value="Fresh">Fresh</option>
                                    <option value="Sold">Sold</option>
                                </select>
                                @error('status')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3 col-lg-4">
                                <label>Timer (Insemination Attempt):</label>
                                <select name="timer" class="form-control">
                                    <option value="">Select</option>
                                    <option value="1st">1st</option>
                                    <option value="2nd">2nd</option>
                                    <option value="3rd">3rd</option>
                                    <option value="4th">4th</option>
                                    <option value="5th">5th</option>
                                </select>
                            </div>
                            <div class="mb-3 col-lg-4">
                                <label>Semen Type:</label>
                                <select name="semen_type" class="form-control">
                                    <option value="">Select</option>
                                    <option value="Sex">Sex</option>
                                    <option value="Regular">Regular</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-lg-4">
                                <label>Insemination Date:</label>
                                <input type="date" name="insemination_date" class="form-control">
                            </div>
                            <div class="mb-3 col-lg-4">
                                <label>Expected Insemination Date:</label>
                                <input type="date" name="exp_insemination_date" class="form-control">
                            </div>
                            <div class="mb-3 col-lg-4">
                                <label>Confirmation Date:</label>
                                <input type="text" name="confirmation_date" class="form-control" placeholder="e.g. Re Check 06-03-2026">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-lg-4">
                                <label>Calf Date:</label>
                                <input type="date" name="calf_date" class="form-control">
                            </div>
                            <div class="mb-3 col-lg-4">
                                <label>Date of Birth:</label>
                                <input type="date" name="date_of_birth" class="form-control">
                            </div>
                            <div class="mb-3 col-lg-4">
                                <label>Notes:</label>
                                <input type="text" name="notes" class="form-control" placeholder="Any additional notes">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Animal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Animal Modal --}}
    <div class="modal fade" id="editAnimalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form method="POST" action="{{ url('update_animal') }}">
                @csrf
                <input type="hidden" name="animal_id" id="edit_animal_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Animal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-lg-4">
                                <label>Tag ID:</label>
                                <input type="text" name="tag_id" id="edit_tag_id" class="form-control" required>
                            </div>
                            <div class="mb-3 col-lg-4">
                                <label>Animal Type:</label>
                                <select name="animal_type" id="edit_animal_type" class="form-control" required>
                                    <option value="Cow">Cow</option>
                                    <option value="Heifer">Heifer</option>
                                    <option value="Calf">Calf</option>
                                </select>
                            </div>
                            <div class="mb-3 col-lg-4">
                                <label>Gender:</label>
                                <select name="gender" id="edit_gender" class="form-control">
                                    <option value="Female">Female</option>
                                    <option value="Male">Male</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-lg-4">
                                <label>Status:</label>
                                <select name="status" id="edit_status" class="form-control" required>
                                    <option value="Milking">Milking</option>
                                    <option value="Pregnant">Pregnant</option>
                                    <option value="Dry">Dry</option>
                                    <option value="Fresh">Fresh</option>
                                    <option value="Sold">Sold</option>
                                </select>
                            </div>
                            <div class="mb-3 col-lg-4">
                                <label>Timer:</label>
                                <select name="timer" id="edit_timer" class="form-control">
                                    <option value="">Select</option>
                                    <option value="1st">1st</option>
                                    <option value="2nd">2nd</option>
                                    <option value="3rd">3rd</option>
                                    <option value="4th">4th</option>
                                    <option value="5th">5th</option>
                                </select>
                            </div>
                            <div class="mb-3 col-lg-4">
                                <label>Semen Type:</label>
                                <select name="semen_type" id="edit_semen_type" class="form-control">
                                    <option value="">Select</option>
                                    <option value="Sex">Sex</option>
                                    <option value="Regular">Regular</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-lg-4">
                                <label>Insemination Date:</label>
                                <input type="date" name="insemination_date" id="edit_insemination_date" class="form-control">
                            </div>
                            <div class="mb-3 col-lg-4">
                                <label>Expected Insemination Date:</label>
                                <input type="date" name="exp_insemination_date" id="edit_exp_insemination_date" class="form-control">
                            </div>
                            <div class="mb-3 col-lg-4">
                                <label>Confirmation Date:</label>
                                <input type="text" name="confirmation_date" id="edit_confirmation_date" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-lg-4">
                                <label>Calf Date:</label>
                                <input type="date" name="calf_date" id="edit_calf_date" class="form-control">
                            </div>
                            <div class="mb-3 col-lg-4">
                                <label>Date of Birth:</label>
                                <input type="date" name="date_of_birth" id="edit_date_of_birth" class="form-control">
                            </div>
                            <div class="mb-3 col-lg-4">
                                <label>Notes:</label>
                                <input type="text" name="notes" id="edit_notes" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Animal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteAnimalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <form action="{{ url('delete_animal') }}" method="POST">
                        @csrf
                        <input type="hidden" name="animal_id" id="delete_animal_id">
                        <h4>Delete Animal?</h4>
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

    {{-- Animals Table --}}
    <section class="card">
        <div class="d-flex align-items-center justify-content-between gap-10 my-10 px-4 flex-wrap">
            <h5 class="fw-bold mb-0">All Animals</h5>
            <button class="btn btn-primary" id="add-animal-btn">
                <i class="las la-plus"></i> Add Animal
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
                        <th>Tag ID</th>
                        <th>Type</th>
                        <th>Gender</th>
                        <th>Status</th>
                        <th>Timer</th>
                        <th>Insemination Date</th>
                        <th>Semen Type</th>
                        <th>Confirmation</th>
                        <th>Calf Date</th>
                        <th>Notes</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($animals as $index => $animal)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $animal->tag_id }}</strong></td>
                        <td>
                            @if($animal->animal_type == 'Cow')
                                <span class="badge bg-primary">Cow</span>
                            @elseif($animal->animal_type == 'Heifer')
                                <span class="badge bg-info">Heifer</span>
                            @else
                                <span class="badge bg-warning text-dark">Calf</span>
                            @endif
                        </td>
                        <td>{{ $animal->gender }}</td>
                        <td>
                            @if($animal->status == 'Milking')
                                <span class="badge bg-success">Milking</span>
                            @elseif($animal->status == 'Pregnant')
                                <span class="badge bg-danger">Pregnant</span>
                            @elseif($animal->status == 'Dry')
                                <span class="badge bg-secondary">Dry</span>
                            @elseif($animal->status == 'Fresh')
                                <span class="badge bg-primary">Fresh</span>
                            @elseif($animal->status == 'Sold')
                                <span class="badge bg-dark">Sold</span>
                            @endif
                        </td>
                        <td>{{ $animal->timer ?? '-' }}</td>
                        <td>{{ $animal->insemination_date ? $animal->insemination_date->format('d M Y') : '-' }}</td>
                        <td>{{ $animal->semen_type ?? '-' }}</td>
                        <td>{{ $animal->confirmation_date ?? '-' }}</td>
                        <td>{{ $animal->calf_date ? $animal->calf_date->format('d M Y') : '-' }}</td>
                        <td>{{ $animal->notes ?? '-' }}</td>
                        <td>
                            <a href="#" class="text-primary action-button edit-btn"
                                data-id="{{ $animal->id }}"
                                data-tag="{{ $animal->tag_id }}"
                                data-type="{{ $animal->animal_type }}"
                                data-gender="{{ $animal->gender }}"
                                data-status="{{ $animal->status }}"
                                data-timer="{{ $animal->timer }}"
                                data-semen="{{ $animal->semen_type }}"
                                data-insemination="{{ $animal->insemination_date?->format('Y-m-d') }}"
                                data-exp="{{ $animal->exp_insemination_date?->format('Y-m-d') }}"
                                data-confirmation="{{ $animal->confirmation_date }}"
                                data-calf="{{ $animal->calf_date?->format('Y-m-d') }}"
                                data-dob="{{ $animal->date_of_birth?->format('Y-m-d') }}"
                                data-notes="{{ $animal->notes }}">
                                <i class="las la-edit"></i>
                            </a>
                            <a href="#" class="text-danger action-button delete-btn ms-2"
                                data-id="{{ $animal->id }}">
                                <i class="las la-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-muted text-center">No animals found. Add your first animal.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Add animal button
    document.getElementById('add-animal-btn').addEventListener('click', function () {
        new bootstrap.Modal(document.getElementById('addAnimalModal')).show();
    });

    // Edit button — populate modal with existing data
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('edit_animal_id').value          = this.dataset.id;
            document.getElementById('edit_tag_id').value             = this.dataset.tag;
            document.getElementById('edit_animal_type').value        = this.dataset.type;
            document.getElementById('edit_gender').value             = this.dataset.gender;
            document.getElementById('edit_status').value             = this.dataset.status;
            document.getElementById('edit_timer').value              = this.dataset.timer;
            document.getElementById('edit_semen_type').value         = this.dataset.semen;
            document.getElementById('edit_insemination_date').value  = this.dataset.insemination;
            document.getElementById('edit_exp_insemination_date').value = this.dataset.exp;
            document.getElementById('edit_confirmation_date').value  = this.dataset.confirmation;
            document.getElementById('edit_calf_date').value          = this.dataset.calf;
            document.getElementById('edit_date_of_birth').value      = this.dataset.dob;
            document.getElementById('edit_notes').value              = this.dataset.notes;

            new bootstrap.Modal(document.getElementById('editAnimalModal')).show();
        });
    });

    // Delete button
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('delete_animal_id').value = this.dataset.id;
            new bootstrap.Modal(document.getElementById('deleteAnimalModal')).show();
        });
    });

});
</script>

@endsection