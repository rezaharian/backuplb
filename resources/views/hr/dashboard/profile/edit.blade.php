@extends('hr.dashboard.layout.layout')

@section('content')

    <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
      
      <div class="container-fluid">
        <div class="page-header min-height-300 border-radius-xl mt-4"  style="background-image: url('/image/logos/cpo.jpg'); background-position-y: 50%;">
            <span class="mask bg-gradient-info opacity-6 fw-bold text-light "></span>
        </div>
        <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
          <div class="row gx-4">
            <div class="col-auto">
             
            </div>
            <div class="col-6 my-auto">
              <div class="h-100">

                <div class="card  p-2  mx-3 mt-1 position-relative z-index-1">


                    <form action="{{ route('hr.profile.update_profile', $profile->id) }}" method="POST">
                        @csrf
                        @method('POST')
                       <div class="bg-info text-center p-2  radius rounded mb-4">
                         <strong>Edit Nama / Password</strong>  
                      </div>
                        <div class="row ">
                        <div class="col-md-12" hidden>
                            <div class="form-group">
                                <strong>ID </strong>
                                <input type="text" name="id" class="form-control" value="{{ $profile->id }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <strong>Nama </strong>
                                <input type="text" name="name" class="form-control" value="{{ $profile->name }}">
                            </div>
                        </div>
                        <div class="col-md-12" hidden>
                            <div class="form-group">
                                <strong>Email </strong>
                                <input type="text" name="email" class="form-control" value="{{ $profile->email }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <strong>Password </strong>
                                <input type="text" name="password" class="form-control" value=""
                                    placeholder="Buat password min 8 Character, Ex:12345678">
                            </div>
                        </div>
                        <div class="col-md-12" hidden> 
                            <strong>Level </strong>
                            <select id="level" name="level" class="form-select"
                                aria-label="Default select example">

                                <option selected value="{{ $profile->level }}">{{ $profile->level }}</option>
                                <option value="Dikrektur">Dikrektur</option>
                                <option value="Manager">Manager</option>
                                <option value="Asmen">Asmen</option>
                                <option value="SPV">SPV</option>
                                <option value="Leader">Leader</option>
                                <option value="Provesional">Provesional</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                                        {{-- <div class="col-md-12">
                                <div class="form-group">
                                    <strong>Role </strong>
                                    <input type="text" name="role_id" class="form-control" 
                                        value="{{ $profile->role_id }}">
                                </div>
                            </div> --}}
                        <div class="col-md-12" hidden>
                            <strong>Role </strong>
                            <select id="role_id" name="role_id" class="form-select"
                                aria-label="Default select example">
                                @if ($profile->role_id == 1)
                                    <option selected value="{{ $profile->role_id }}">Admin</option>
                                @elseif($profile->role_id == 2)
                                    <option selected value="{{ $profile->role_id }}">Bos</option>
                                @elseif($profile->role_id == 3)
                                    <option selected value="{{ $profile->role_id }}">HR</option>
                                @elseif($profile->role_id == 4)
                                    <option selected value="{{ $profile->role_id }}">Pegawai</option>
                                @elseif($profile->role_id == 5)
                                    <option selected value="{{ $profile->role_id }}">Trainer</option>
                                @elseif($profile->role_id == 6)
                                    <option selected value="{{ $profile->role_id }}">QC</option>
                                @elseif($profile->role_id == 7)
                                    <option selected value="{{ $profile->role_id }}">Produksi</option>
                                @else
                                    <option selected value="{{ $profile->role_id }}">Umum</option>
                                @endif
                                <option value="1">Admin</option>
                                <option value="2">Bos</option>
                                <option value="3">HR</option>
                                <option value="4">Pegawai</option>
                                <option value="5">Trainer</option>
                                <option value="6">QC</option>
                                <option value="7">Produksi</option>
                                <option value="8">Umum</option>
                            </select>
                        </div>

                        <div class="mb-1 mt-1 item-center text-center   ">
                            <button type="submit" class="btn btn-md bg-gradient-info rounded-4 border "> Save
                            </button>

                        </div>

                    </div>
                </div>

            
              </div>
            </div> 
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
              <div class="nav-wrapper position-relative end-0">
                <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
            

                  @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (Session::has('success'))
            <div class="alert alert-info text-center">
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif
             
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


@endsection