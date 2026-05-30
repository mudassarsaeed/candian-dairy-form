@extends('layouts.master' ,['page_title' => 'Dashboard'])
@push('page-css')
<link rel="stylesheet" href="{{asset('assets/css/adminpage_css.css')}}">
@endpush
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div id="kt_content_container" class="">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Dashboard
                        <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    </h1>
                </div>
            </div>
        </div>

        <div class="dashboard">

            {{-- Stats Cards --}}
            <div class="row justify-content-center">
                <div class="mb-2 col-sm-5 col-lg-3">
                    <a href="{{ url('manage-customers') }}" class="text-decoration-none">
                        <div class="card text-center">
                            <div class="mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="auto" height="100" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                                </svg>
                            </div>
                            <h4>{{ $customersCount }}</h4>
                            <h3>TOTAL CUSTOMERS</h3>
                        </div>
                    </a>
                </div>

                <div class="mb-2 col-sm-5 col-lg-3">
                    <a href="{{ url('manage-animals') }}" class="text-decoration-none">
                        <div class="card text-center">
                            <div class="mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="auto" height="100" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                    <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                                </svg>
                            </div>
                            <h4>{{ $animalsCount }}</h4>
                            <h3>TOTAL ANIMALS</h3>
                        </div>
                    </a>
                </div>

                <div class="mb-2 col-sm-5 col-lg-3">
                    <a href="{{ url('manage-calfs') }}" class="text-decoration-none">
                        <div class="card text-center">
                            <div class="mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="auto" height="100" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                                    <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z"/>
                                    <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z"/>
                                    <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5"/>
                                </svg>
                            </div>
                            <h4>{{ $calfsCount }}</h4>
                            <h3>TOTAL CALFS</h3>
                        </div>
                    </a>
                </div>

                <div class="mb-2 col-sm-5 col-lg-3">
                    <a href="{{ url('milk-report') }}" class="text-decoration-none">
                        <div class="card text-center">
                            <div class="mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="auto" height="100" fill="currentColor" class="bi bi-droplet-fill" viewBox="0 0 16 16">
                                    <path d="M8 16a6 6 0 0 0 6-6c0-1.655-1.122-2.904-2.432-4.362C10.254 4.176 8.75 2.503 8 0c0 0-6 5.686-6 10a6 6 0 0 0 6 6M6.646 4.646l.708.708c-.29.29-1.128 1.311-1.907 2.87l-.894-.448c.82-1.641 1.717-2.753 2.093-3.13"/>
                                </svg>
                            </div>
                            <h4>{{ $todayMilk }} L</h4>
                            <h3>TODAY'S MILK</h3>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Quick Access Buttons --}}
{{-- Quick Access Buttons --}}
        <div class="row justify-content-center mt-4 mb-4">
            <div class="col-12">
                <h4 class="fw-bold text-dark mb-3 px-2">Quick Access</h4>
            </div>

            <div class="mb-3 col-sm-6 col-lg-4">
                <a href="{{ url('manage-expenses') }}" class="text-decoration-none">
                    <div class="card text-center p-4" style="background: linear-gradient(135deg, #c0392b, #e74c3c); color: white; border-radius: 16px; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                        <div class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="white" class="bi bi-receipt" viewBox="0 0 16 16">
                                <path d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27m.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0z"/>
                                <path d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5"/>
                            </svg>
                        </div>
                        <h4 class="fw-bold text-white mb-1">Manage Expenses</h4>
                        <p class="text-white mb-0" style="opacity:0.85; font-size:13px;">Track and manage all farm expenses</p>
                    </div>
                </a>
            </div>

            <div class="mb-3 col-sm-6 col-lg-4">
                <a href="{{ url('customer-milk-records') }}" class="text-decoration-none">
                    <div class="card text-center p-4" style="background: linear-gradient(135deg, #1a5276, #2980b9); color: white; border-radius: 16px; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                        <div class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="white" class="bi bi-droplet-fill" viewBox="0 0 16 16">
                                <path d="M8 16a6 6 0 0 0 6-6c0-1.655-1.122-2.904-2.432-4.362C10.254 4.176 8.75 2.503 8 0c0 0-6 5.686-6 10a6 6 0 0 0 6 6M6.646 4.646l.708.708c-.29.29-1.128 1.311-1.907 2.87l-.894-.448c.82-1.641 1.717-2.753 2.093-3.13"/>
                            </svg>
                        </div>
                        <h4 class="fw-bold text-white mb-1">Customers Milk Records</h4>
                        <p class="text-white mb-0" style="opacity:0.85; font-size:13px;">Daily milk delivery attendance</p>
                    </div>
                </a>
            </div>

            <div class="mb-3 col-sm-6 col-lg-4">
                <a href="{{ url('customer-bills') }}" class="text-decoration-none">
                    <div class="card text-center p-4" style="background: linear-gradient(135deg, #1e8449, #27ae60); color: white; border-radius: 16px; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                        <div class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="white" class="bi bi-file-earmark-text" viewBox="0 0 16 16">
                                <path d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5"/>
                                <path d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                            </svg>
                        </div>
                        <h4 class="fw-bold text-white mb-1">Customer Monthly Bills</h4>
                        <p class="text-white mb-0" style="opacity:0.85; font-size:13px;">Generate and manage monthly bills</p>
                    </div>
                </a>
            </div>
            <div class="mb-3 col-sm-6 col-lg-4">
                <a href="{{ url('manage-customers') }}" class="text-decoration-none">
                    <div class="card text-center p-4" style="background: linear-gradient(135deg, #117a65, #1abc9c); color: white; border-radius: 16px; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                        <div class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="white" class="bi bi-people-fill" viewBox="0 0 16 16">
                                <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                            </svg>
                        </div>
                        <h4 class="fw-bold text-white mb-1">Manage Customers</h4>
                        <p class="text-white mb-0" style="opacity:0.85; font-size:13px;">View and manage all customers</p>
                    </div>
                </a>
            </div>
            <div class="mb-3 col-sm-6 col-lg-4">
                <a href="{{ url('manage-animals') }}" class="text-decoration-none">
                    <div class="card text-center p-4" style="background: linear-gradient(135deg, #6c3483, #9b59b6); color: white; border-radius: 16px; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                        <div class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="white" class="bi bi-grid" viewBox="0 0 16 16">
                                <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5zm8 0A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5zm-8 8A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5zm8 0A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5z"/>
                            </svg>
                        </div>
                        <h4 class="fw-bold text-white mb-1">Manage Animals</h4>
                        <p class="text-white mb-0" style="opacity:0.85; font-size:13px;">View and manage all farm animals</p>
                    </div>
                </a>
            </div>

            <div class="mb-3 col-sm-6 col-lg-4">
                <a href="{{ url('manage-calfs') }}" class="text-decoration-none">
                    <div class="card text-center p-4" style="background: linear-gradient(135deg, #b7950b, #f39c12); color: white; border-radius: 16px; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                        <div class="mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="white" class="bi bi-stars" viewBox="0 0 16 16">
                                <path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.73 1.73 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.73 1.73 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.73 1.73 0 0 0 3.407 2.31zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"/>
                            </svg>
                        </div>
                        <h4 class="fw-bold text-white mb-1">Manage Calfs</h4>
                        <p class="text-white mb-0" style="opacity:0.85; font-size:13px;">View and manage all farm calfs</p>
                    </div>
                </a>
            </div>

        </div>

        </div>
    </div>
</div>
@endsection