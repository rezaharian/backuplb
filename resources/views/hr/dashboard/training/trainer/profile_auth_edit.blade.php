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


                    <form action="{{ route('trainer.profile_auth_update', $profile->id) }}" method="POST">
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

                {{-- <h5 class="mb-1">
                  {{ $profile->name }} | {{ $profile->level }}
                </h5>
                <p class="mb-0 font-weight-bold text-sm">
                  {{ $profile->email }}
                </p> --}}
              </div>
            </div> 
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
              <div class="nav-wrapper position-relative end-0">
                <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                  {{-- <li class="nav-item">
                    <a class="nav-link mb-0 px-0 py-1 active " data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="true">
                      <svg class="text-dark" width="16px" height="16px" viewBox="0 0 42 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                          <g transform="translate(-2319.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                            <g transform="translate(1716.000000, 291.000000)">
                              <g transform="translate(603.000000, 0.000000)">
                                <path class="color-background" d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z">
                                </path>
                                <path class="color-background" d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z" opacity="0.7"></path>
                                <path class="color-background" d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z" opacity="0.7"></path>
                              </g>
                            </g>
                          </g>
                        </g>
                      </svg>
                      <span class="ms-1">{{ $profile->role_id }}                      </span>
                    </a>
                  </li> --}}

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