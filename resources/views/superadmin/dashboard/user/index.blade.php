@extends('superadmin.dashboard.layout.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

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
    <div class="section px-0 ">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-plain rounded-0 border-primary">
                    <div class="card-header m-0  rounded-0 bg-primary text-light">
                        <h4 class="m-0">Tambah User </h4>
                    </div>
                    <div class="card-body rounded-0">
                        <form role="form form" action="{{ url('/superadmin/dashboard/user/store') }}" method="POST">
                            @csrf
                            <div class="form-group form-group-sm">
                                <label class="m-0" for="name">Name</label>
                                <input type="text" id="name" name="name" class="form-control form-control-sm"
                                    placeholder="Enter  name">
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="m-0" for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control form-control-sm"
                                    placeholder="Enter your email">
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="m-0" for="password">Password</label>
                                <input type="password" id="password" name="password" class="form-control form-control-sm"
                                    placeholder="Enter your password">
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="m-0" for="level">Level</label>
                                <select id="level" name="level" class="form-control form-control-sm">
                                    <option selected>Select Level...</option>
                                    <option value="Dikrektur">Dikrektur</option>
                                    <option value="Manager">Manager</option>
                                    <option value="Asmen">Asmen</option>
                                    <option value="SPV">SPV</option>
                                    <option value="Leader">Leader</option>
                                    <option value="Provesional">Provesional</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="m-0" for="role_id">Role</label>
                                <select id="role_id" name="role_id" class="form-control form-control-sm">
                                    <option selected>Select Role...</option>
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
                            <div class="text-center">
                                <button type="submit" class="btn btn-sm rounded-0 mb-4 btn-primary">Tambah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <div class="col-md-8">
                <div class="card card-plain rounded-0 border-primary me-1">
                    <div style="height:535px;overflow:auto;">
                        <table id="myTable" class="align-items-center mb-0  table-sm">
                            <thead class="bg-primary text-center text-uppercase text-light shadow font-weight-bolder sticky-top">
                                <tr style="font-size: 8pt; background-color: rgb(5, 109, 255);" class="text-light">
                                    <th class="">ID</th>
                                    <th class=" ps-2">Name
                                    </th>
                                    <th class="text-center ">
                                        Email
                                    </th>
                                    <th class="text-center text-uppercase text-dark text-xxs font-weight-bolder  opacity-7"
                                        hidden>
                                        Password
                                    </th>
                                    <th class="text-center ">
                                        Level
                                    </th>
                                    <th class="text-center ">
                                        Role ID
                                    </th>
                                    <th class="text-center ">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="">
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
                                        <td hidden>
                                            <div class="d-flex px-2 py-1">
                                                <p class="text-xs text-secondary  mb-0">{{ $users->password }}</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <p class="text-xs text-secondary  mb-0">{{ $users->level }}</p>
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
                                        <td class="px-2 py-1">
                                            <div class="row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-3">
                                                    <a href="{{ route('superadmin.edit.user', $users->id) }}" class="btn btn-link m-0 p-0">
                                                        <i class="fa-solid fa-pen text-primary"></i>
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <form class="" action="{{ route('superadmin.destroy.user', $users->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link text-danger mx-0 m-0 p-0" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#myTable, #myTable2').DataTable({
                "order": [
                    [0, "asc"]
                ], // Mengurutkan berdasarkan kolom Tanggal (indeks kolom 4) secara descending
                "pageLength": 100, // Menetapkan entri default menjadi 100
                "lengthChange": false // Menyembunyikan pilihan "Show [n] entries"
            });
        });
    </script>
    
    <style>
        ::-webkit-scrollbar {
            width: 0.1em;
            /* Ubah lebar scrollbar sesuai kebutuhan */
            height: 0.1em;
            /* Ubah tinggi scrollbar sesuai kebutuhan */
        }

        ::-webkit-scrollbar-thumb {
            background-color: transparent;
        }

        div.dataTables_wrapper input[type="search"] {
            border: 1px solid blue !important;
            margin-top: 2pt;
        }


        table {
            border-collapse: collapse;
            width: 100%;
        }

        table td,
        table th {
            padding: 8px;
        }

        table tffh {
            background-color: #f2f2f2;
            /* Warna latar belakang header dapat disesuaikan */
        }

        table tbody tr:hover {
            background-color: #f5f5f5;
            /* Warna latar belakang baris tbody saat hover */
        }

        table tbody tr:hover td {
            background-color: #ebebeb;
            /* Warna latar belakang sel-sel dalam baris tbody saat hover */
        }


        table td {
            padding: 0.5em;
            line-height: 1;

            /* Sesuaikan dengan kebutuhan Anda */
        }

        .form-control {
            border-radius: 0;
        }

        .card-plain {
            opacity: 0;
            animation-name: fade-in;
            animation-duration: 0.3s;
            animation-fill-mode: forwards;
            animation-timing-function: ease-in-out;
            animation-delay: 0.3s;
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    
        .btn-delete {
            background-color: rgb(255, 255, 255);
            color: red;
        }

        .btn-editt {
            background-color: rgb(255, 255, 255);
            color: rgb(4, 42, 255);
        }

        .btn-view {
            background-color: rgb(255, 255, 255);
            color: rgb(29, 4, 255);
        }

        .btn-delete:hover {
            background-color: red;
            transform: scale(1.1);
            color: white;
        }

        .btn-editt:hover {
            background-color: rgb(4, 42, 255);
            transform: scale(1.1);
            color: white;
        }

        .btn-view:hover {
            background-color: rgb(29, 4, 255);
            transform: scale(1.1);
            color: white;
        }


       
    </style>
@endsection
