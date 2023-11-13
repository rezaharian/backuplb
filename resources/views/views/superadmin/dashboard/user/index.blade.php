@extends('superadmin.dashboard.layout.layout')

@section('content')
    <div class="col-md-4">
        <button type="button" class="btn btn-block btn-default mb-3 border" data-bs-toggle="modal"
            data-bs-target="#modal-form">Tambah</button>
        <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="card card-plain">
                            <div class="card-header pb-0 text-left">
                                <h4 class="font-weight-bolder text-info text-gradient">Tambah User</h4>
                            </div>
                            <div class="card-body">
                                <form role="form text-left" action="{{ url('/superadmin/dashboard/user/store') }}"
                                    method="POST">
                                    @csrf
                                    <label>Nama</label>
                                    <div class="input-group mb-3">
                                        <input type="text" id="name" name="name" class="form-control"
                                            placeholder="Name">
                                    </div>
                                    <label>Email</label>
                                    <div class="input-group mb-3">
                                        <input type="email" id="email" name="email" class="form-control"
                                            placeholder="Email">
                                    </div>
                                    <label>Password</label>
                                    <div class="input-group mb-3">
                                        <input type="text" id="password" name="password" class="form-control"
                                            placeholder="Password">
                                    </div>
                                    <label>Role</label>
                                    <select  id="role_id" name="role_id" class="form-select" aria-label="Default select example">
                                        <option selected>Pilih Role...</option>
                                        <option value="1">Admin</option>
                                        <option value="2">Bos</option>
                                        <option value="3">HR</option>
                                        <option value="4">Pegawai</option>
                                        <option value="5">Trainer</option>
                                        <option value="6">QC</option>
                                        <option value="7">Produksi</option>
                                        <option value="8">Umum</option>
                                      </select>
                                    <div class="text-center">
                                        <button type="submit"
                                            class="btn btn-round bg-gradient-info btn-lg w-100 mt-4 mb-0">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    <div class="card px-3">
        <div class="table-responsive">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7">ID</th>
                        <th class="text-uppercase text-dark text-xxs font-weight-bolder opacity-7 ps-2">Name</th>
                        <th class="text-center text-uppercase text-dark text-xxs font-weight-bolder opacity-7">Email</th>
                        <th class="text-center text-uppercase text-dark text-xxs font-weight-bolder  opacity-7">Password</th>
                        <th class="text-center text-uppercase text-dark text-xxs font-weight-bolder opacity-7">Role ID</th>
                        <th class="text-center text-uppercase text-dark text-xxs font-weight-bolder opacity-7">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user as $users)
                        <tr>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <p class="text-xs text-secondary mb-0">{{ $users->id }}</p>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <p class="text-xs text-secondary mb-0">{{ $users->name }}</p>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <p class="text-xs text-secondary mb-0">{{ $users->email }}</p>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <p class="text-xs text-secondary  mb-0">{{ $users->password }}</p>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    @if ($users->role_id == 1)
                                        <p class="text-xs text-secondary mb-0">SuperAdmin</p>
                                    @elseif($users->role_id == 2)
                                        <p class="text-xs text-secondary mb-0">Bos</p>
                                    @elseif($users->role_id == 3)
                                        <p class="text-xs text-secondary mb-0">HR</p>
                                    @elseif($users->role_id == 4)
                                        <p class="text-xs text-secondary mb-0">Pegawai</p>
                                    @elseif($users->role_id == 5)
                                        <p class="text-xs text-secondary mb-0">Trainer</p>
                                    @elseif($users->role_id == 6)
                                        <p class="text-xs text-secondary mb-0">QC</p>
                                    @elseif($users->role_id == 7)
                                        <p class="text-xs text-secondary mb-0">Produksi</p>
                                    @else
                                        <p class="text-xs text-secondary mb-0">Umum</p>
                                    @endif
                                </div>
                            </td>

                            <td class="d-flex px-2 py-1">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-outline-info">
                                        <a href="{{ route('superadmin.edit.user', $users->id) }}"
                                            class="text-gradient-info font-weight-bold ">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <form class="" action="{{ route('superadmin.destroy.user', $users->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger text-danger mx-0"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            <i class="fas fa-trash "></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>






    <script>
        $('#modal-form-edit').on('show.bs.modal', function(event) {
            // event.relatedtarget menampilkan elemen mana yang digunakan saat diklik.
            var button = $(event.relatedTarget)

            // data-data yang disimpan pada tombol edit dimasukkan ke dalam variabelnya masing-masing 
            var d_id = button.data('id')
            var d_nama = button.data('name')
            var d_email = button.data('email')
            var d_password = button.data('password')
            var d_role_id = button.data('role_id')
            var modal = $(this)

            //variabel di atas dimasukkan ke dalam element yang sesuai dengan idnya masing-masing
            modal.find('#id').val(d_id)
            modal.find('#name').val(d_nama)
            modal.find('#email').val(d_email)
            modal.find('#password').val(d_password)
            modal.find('#role_id').val(d_role_id)
        })
    </script>
    </body>

    </html>
@endsection
