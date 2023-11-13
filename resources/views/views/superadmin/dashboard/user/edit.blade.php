@extends('superadmin.dashboard.layout.layout')

@section('content')




<div class="row ">
    <div class="col-md-4">

    </div>
<div class="col-md-4 bg-gradient-info rounded-5 border shadow-lg">
    

    <div class="card-header p-0 mx-3 mt-1 position-relative z-index-1">

        <div class="text-center  mb-3 ">
            <h3 class="bg-light  text-info rounded-5"><b> Edit User</b> 
                {{-- <br> <u>  <small> {{ $user->name }} </small>  --}}
                </h5></u>
        </div>
        <form action="{{ route('superadmin.update.user', $user->id) }}" method="POST" >
            @csrf
            @method('POST')
            <div class="row-">
                <div class="col-md-12" hidden>
                    <div class="form-group">
                        <strong>ID </strong>
                        <input type="text" name="id" class="form-control"  
                        value="{{ $user->id }}">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <strong>Nama </strong>
                        <input type="text" name="name" class="form-control" 
                            value="{{ $user->name }}">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <strong>Email </strong>
                        <input type="text" name="email" class="form-control" 
                            value="{{ $user->email }}">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <strong>Password </strong>
                        <input type="text" name="password" class="form-control" 
                            placeholder="Buat password">
                    </div>
                </div>
                {{-- <div class="col-md-12">
                    <div class="form-group">
                        <strong>Role </strong>
                        <input type="text" name="role_id" class="form-control" 
                            value="{{ $user->role_id }}">
                    </div>
                </div> --}}
                <div class="col-md-12">
                    <select  id="role_id"    name="role_id" class="form-select" aria-label="Default select example">
                        @if ($user->role_id == 1)
                        <option selected value="{{ $user->role_id }}">SuperAdmin</option>
                        @elseif($user->role_id == 2)
                        <option selected value="{{ $user->role_id }}">Bos</option>
                        @else
                        <option selected value="{{ $user->role_id }}">Pegawai</option>
                        @endif
                        <option value="1">Admin</option>
                        <option value="2">Bos</option>
                        <option value="3">HR</option>
                        <option value="4">Pegawai</option>
                        <option value="5">Trainer</option>
                      </select>
                </div>
              
            <div class="mb-2 mt-3 item-center text-center   ">
                <a href="../../../../superadmin/dashboard/user"><button  class="btn btn-sm bg-gradient-secondary rounded-4 border">Back</button></a> 
                <button type="submit" class="btn btn-sm bg-gradient-info rounded-4 border "> Save </button>

            </div>
        </form>

</div>
</div>
</div>


@endsection