@extends('layouts.master' ,['page_title' => 'Dashboard'])
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">

    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center justify-content-between w-100 me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">All Esxpenses
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                </h1>
            </div>
        </div>
    </div>

    <div class="modal fade" id="AddCompany" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{url('submit_expense')}}" enctype="multipart/form-data" class="addForm">
                @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Farm Expense</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                    <div class="mb-3 col-lg-6">
                        <label>Category:</label>
                        <select name="cat" id="cat" class="form-control" required>
                            <option value="" selected>Select Category</option>
                            @foreach ($expensecategories as $expensecategory)
                                <option value="{{ $expensecategory->id }}">{{ $expensecategory->name }}</option>
                            @endforeach
                            <option value="other">Other</option>
                        </select>
                    </div>

                    {{-- Other Category field - hidden by default --}}
                    <div class="mb-3 col-lg-6" id="other-category-field" style="display:none;">
                        <label>Specify Other Category: <span class="text-danger">*</span></label>
                        <input type="text" name="other_category" id="other_category"
                            class="form-control" placeholder="Enter category name">
                        @error('other_category')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label>Order Date:</label>
                        <input type="date" class="form-control" name="order_date" required placeholder="Enter Order Date">
                        @error('order_date')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label>Arrival Date:</label>
                            <input name="arival_date" type="date" class="form-control" required placeholder="Enter Arrival Date" data-type="add">
                            @error('arival_date')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label>Ending Date:</label>
                            <input name="enddate" type="date" class="form-control" required placeholder="Enter Ending Date" data-type="add">
                            @error('enddate')
                            <span class="text-danger">{{$message}}</span>
                                @enderror                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label>Unit:</label>
                            <select name="unit" id="unit" style="width: 160px;">
                            <option value="" selected>Select Unit</option>
                           
                                <option value="Tons">Tons</option>
                                <option value="KG">KG</option>
                          
                        </select>
                                           </div>
                        
                        <div class="mb-3 col-lg-6">
                            <label for="exampleFormControlInput1" class="form-label">Number of Bags/Bails</label>
                            <input name="nbbs" type="text" class="form-control"  required placeholder="Enter Bags/Bails" data-type="add">
                            @error('bags')
                            <span class="text-danger">{{$message}}</span>
                                @enderror   
                                                       
                        </div>
                                
                    </div>
                     <div class="row">
                        {{-- <div class="mb-3 col-lg-6">
                            <label>Bails:</label>
                            <input name="bails" type="text" class="form-control"  placeholder="Enter Bails" data-type="add">
                            @error('bails')
                            <span class="text-danger">{{$message}}</span>
                                @enderror                        </div> --}}
                        
                        <div class="mb-3 col-lg-6">
                            <label for="exampleFormControlInput1" class="form-label">Unit Price</label>
                             <input name="unit_price" type="text" class="form-control" required placeholder="Enter Unit Price" data-type="add">
                            @error('unit_price')
                            <span class="text-danger">{{$message}}</span>
                                @enderror        
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label>Total Amount:</label>
                            <input name="toatl" type="text" class="form-control" required placeholder="Enter Total Amount" data-type="add">
                            @error('toatl')
                            <span class="text-danger">{{$message}}</span>
                                @enderror                        </div>
                                
                    </div>
                       <div class="row">
                        
                        
                        <div class="mb-3 col-lg-6">
                            <label for="exampleFormControlInput1" class="form-label">Upload Receipt</label>
                            <input type="file" name="file" />
                                @error('logo')
                                <span class="text-danger">{{$message}}</span>
                                    @enderror                       
                        </div>
                                
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
            <button  class="btn btn-primary make-button">Add Farm Expense</button>
        </div>
        <div class="table-responsive">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th>Ordered Date</th>
                        <th>Arrival Date</th>
                        <th>Ending Date</th>
                        <th>Unit</th>
                        <th>Bags/Bails</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                        <th>Receipt</th>
                        {{-- <th colspan="2">Action</th> --}}
                       

                    </tr>
                </thead>
                <tbody>
                    @foreach ($expenses as $expense)
                    <tr>
                        <td>{{$expense->id}}</td>
                        <td>
                        @if($expense->cat_id)
                            {{ $expense->category->name ?? 'N/A' }}
                        @elseif($expense->other_category)
                            {{ $expense->other_category }} <span class="badge bg-secondary">Other</span>
                        @else
                            N/A
                        @endif
                        </td>
                        <td>{{$expense->order_date}}</td>
                        <td>{{$expense->arrival_date}}</td>
                        <td>{{$expense->ending_date}}</td>
                        <td>{{$expense->unit}}</td>
                        <td>{{$expense->number_bags_bails}}</td>
                        <td>{{$expense->unit_price}}</td>
                        <td>{{$expense->total}}</td>
                        <td>
                            @if ($expense->receipt)
                            <a href="{{url('/uploads/receipt/'.$expense->receipt)}}" target="_blank" class="text-primary action-button"><img src="/uploads/receipt/{{$expense->receipt}}" width="80"></a>
                             @endif
                        </td>
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

    // Show/hide other category field
document.getElementById('cat').addEventListener('change', function () {
    const otherField = document.getElementById('other-category-field');
    const otherInput = document.getElementById('other_category');

    if (this.value === 'other') {
        otherField.style.display = 'block';
        otherInput.setAttribute('required', 'required');
    } else {
        otherField.style.display = 'none';
        otherInput.removeAttribute('required');
        otherInput.value = '';
    }
});
    </script>
@endsection
