@extends('layouts.master' ,['page_title' => 'Dashboard'])
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">

    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center justify-content-between w-100 me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">All Customers
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                </h1>
            </div>
        </div>
    </div>

    <div class="modal fade" id="AddCompany" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{url('submit_customer')}}" enctype="multipart/form-data" class="addForm">
                @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Manage Customer</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                    <div class="mb-3 col-lg-6">
                       <label>Name:</label>
                        <input type="text" class="form-control" name="name" required placeholder="Enter Customer Name">
                        @error('name')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label>Email:</label>
                        <input type="email" class="form-control" name="email"  placeholder="Enter Customer Email">
                        @error('email')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label>Address:</label>
                            <input name="address" type="text" class="form-control" required placeholder="Enter Customer Address">
                            @error('address')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label>Phone:</label>
                            <input name="phone" type="text" class="form-control" required placeholder="Enter Customer Phone" data-type="add">
                            @error('phone')
                            <span class="text-danger">{{$message}}</span>
                                @enderror                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label>WhatsApp:</label>
                            <input name="whatsapp" type="text" class="form-control"  placeholder="Enter Customer WhatsApp" data-type="add">
                              @error('whatsapp')
                            <span class="text-danger">{{$message}}</span>
                                @enderror                 
                        </div>
                        
                        <div class="mb-3 col-lg-6">

                         <label>Customer Type:</label>
                        <select class="form-control" name="customer_type" required>
                            <option value="">Select Customer Type</option>
                            <option value="Home Delivery">Home Delivery</option>
                            <option value="Bulk Pick Up">Bulk Pick Up</option>
                            <option value="Bulk Delivery">Bulk Delivery</option>
                        </select>
                    @error('customer_type')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                                         
                        </div>
                                
                    </div>
                     <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label>Price Per Liter:</label>
                            <input name="price" type="text" class="form-control"  placeholder="Enter Price Per Liter" data-type="add">
                            @error('price')
                            <span class="text-danger">{{$message}}</span>
                                @enderror                        </div>
                       <div class="mb-3 col-lg-6">
                            <label>Liter Per Day:</label>
                            <input name="liter_per_day" type="text" class="form-control"  placeholder="Enter Liter Per Day" data-type="add">
                            @error('liter_per_day')
                            <span class="text-danger">{{$message}}</span>
                                @enderror                        </div> 
                        
                        
                    
                    </div>
                      



                </div>
                <div class="modal-footer col-lg-9">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit </button>
                </div>
            </div>
            </form>
        </div>
    </div>

  
    {{-- for deletion --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-body">
              <div class="modal-data">
                <form action="{{url('/delete_company')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <img src="assets/images/warning.svg" alt="">
                    <input type="hidden" name="commpanyId" id="commpanyId" class="commpanyId">
                    <h3>Delete <b>Company</b></h3> 
                    <p>You're going to delete the <b>"Company"</b></p>
                    <div class="modal-action">
                        <button type="button" class="btn btn-action-cancel" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-action-approve">Yes</button>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    <section class="card">
        <div class="d-flex align-items-center gap-10 my-10 flex-wrap">
            {{-- <h3>Search By Date:</h3>
            <div class="d-flex align-items-center gap-5 gap-sm-10 flex-wrap flex-sm-nowrap">
                <input type="date" class="form-control ">
                <h3>To</h3>
                <input type="date" class="form-control">

            </div> --}}
            <button  class="btn btn-primary make-button">Add Customer</button>
        </div>
        <div class="table-responsive">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer Type</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>WhatsApp</th>
                        <th>Price Per Liter</th>
                        <th>Liter Per Day</th>
                                             {{-- <th colspan="2">Action</th> --}}
                       

                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                    <tr>
                        <td>{{$customer->id}}</td>
                        <td>
                            {{-- Badge color based on type --}}
                            @if($customer->customer_type == 'Home Delivery')
                                <span class="badge bg-success">Home Delivery</span>
                            @elseif($customer->customer_type == 'Bulk Pick Up')
                                <span class="badge bg-warning text-dark">Bulk Pick Up</span>
                            @elseif($customer->customer_type == 'Bulk Delivery')
                                <span class="badge bg-primary">Bulk Delivery</span>
                            @else
                                <span class="badge bg-secondary">N/A</span>
                            @endif
                        </td>
                        
                        <td>{{$customer->name}}</td>
                        <td>{{$customer->email}}</td>
                        <td>{{$customer->address}}</td>
                        <td>{{$customer->phone}}</td>
                        <td>{{$customer->whatsapp}}</td>
                        <td>{{$customer->price_liter}}</td>
                        <td>{{$customer->liter_per_day}}</td>
                       
                        {{-- <td>
                            <a href="{{url('edit_company/'.$expense->id)}}" class="text-primary action-button">
                                <i class="las la-edit"></i>
                            </a>
                            <a href="#"  class="text-danger action-button delete-button" data-id="{{$expense->id}}">
                                <i class="las la-trash"></i>
                            </a>
                        </td> --}}
                      
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>

   {{-- delete modal --}}
   {{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ url('import_customers') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Customers from Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="fw-bold">Select Excel File (.xlsx, .xls, .csv):</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                    </div>
                    {{-- Column mapping guide --}}
                    <div class="alert alert-info">
                        <strong>Excel columns required:</strong><br>
                        Name, Address, Phone, WhatsApp, Price / Liter (Rs), Liter per day, Customer Type
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">
                        <i class="las la-file-import"></i> Import
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Add import button next to your existing Add Customer button --}}
<button class="btn btn-success" id="import-btn">
    <i class="las la-file-excel"></i> Import from Excel
</button> 
       
    <script>
        //for add 
        let showbutton = document.querySelectorAll('.make-button');
        showbutton.forEach(el => {
            el.addEventListener('click', function(){
                // showing the Modal
                var modal = new bootstrap.Modal(document.getElementById('AddCompany'));
                modal.show();
            })
        })

        //for deletion
        let deleteButton = document.querySelectorAll('.delete-button');
    deleteButton.forEach(el => {
        el.addEventListener('click', function(){
            let commpanyId = this.getAttribute('data-id');
            document.getElementById('commpanyId').value = commpanyId;
            // showing the Modal
            var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        })
    })

    document.getElementById('import-btn').addEventListener('click', function () {
    new bootstrap.Modal(document.getElementById('importModal')).show();
});
    </script>
@endsection
