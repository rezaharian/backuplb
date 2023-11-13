@extends('superadmin.dashboard.layout.layout')

@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">


    <div class="container">
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

        <form action="{{ route('superadmin.update.user', $user->id) }}" method="POST">
            @csrf
            @method('POST')

            <div class="row p-1 ">
               
                <div class="col-md-6 card-plain">

                    <button type="button" class="btn btn-primary text-light fw-bold btn-sm rounded-0 m-0 mb-1"
                        id="btn-tambah">TAMBAH </button>
                    <div class="card">

                        <table class="table table-striped bg-light">
                            <thead class=" bg-primary fw-bold text-light">
                                <tr>

                                    <td>Akses Bagian</td>
                                    <td>Delete</td>
                                </tr>
                            </thead>
                            <tbody id="tbl-barang-body">
                                @php $i=1; @endphp
                                @foreach ($data as $item)
                                    <tr>
                                        <td hidden>
                                            <input type="text" name="int_akses[]"
                                                value="{{ $int_akses }}[{{ $i++ }}]"
                                                class="form-control form-control-sm">
                                        </td>
                                        <td hidden>
                                            <input class="form-control form-control-sm" type=""
                                                name="id_akses_user[]" value="{{ $item->id }}" id="" readonly>
                                        </td>
                                        <td hidden>{{ $item->id }}</td>
                                        <td>
                                            <input type="text" name="bagian[]" value="{{ $item->bagian }}"
                                                class="form-control form-control-sm rounded-0">

                                        </td>

                                        <td class="text-center">
                                            <a class="btn btn-md m-0" onclick="return confirm('Apakah Anda Yakin ?');"
                                                href="{{ route('superadmin.delete_akses.user', $item->id) }}">
                                                <i class="fas fa-trash text-danger"></i>
                                            </a>
                                        </td>
                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                </div>

                <div class="col-md-6 ">
                    <div class="card card-plain border-primary rounded-0">

                        <div class="card-header  rounded-0 bg-primary text-light ">
                            <h5>Edit User</h5>
                    </div>
                    <div class="card-body  rounded-0">
                        <div class="row-">
                            <div class="col-md-12" hidden>
                                <div class="form-group form-group-sm">
                                    <strong>ID </strong>
                                    <input type="text" name="id" class="form-control rounded-0 form-control-sm" value="{{ $user->id }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group form-group-sm">
                                    <strong>Nama </strong>
                                    <input type="text" name="name" class="form-control rounded-0 form-control-sm" value="{{ $user->name }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group form-group-sm">
                                    <strong>Email </strong>
                                    <input type="text" name="email" class="form-control rounded-0 form-control-sm" value="{{ $user->email }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group form-group-sm">
                                    <strong>Password </strong>
                                    <input type="text" name="password" class="form-control rounded-0 form-control-sm" value="12345678"
                                    placeholder="Buat password">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <strong>Level </strong>
                                <select id="level" name="level" class="form-select form-select-sm rounded-0"
                                aria-label="Default select example">
                                
                                <option selected value="{{ $user->level }}">{{ $user->level }}</option>
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
                                    value="{{ $user->role_id }}">
                                </div>
                            </div> --}}
                            <div class="col-md-12">
                                <strong>Role </strong>
                                <select id="role_id" name="role_id" class="form-select form-select-sm rounded-0"
                                aria-label="Default select example">
                                @if ($user->role_id == 1)
                                <option selected value="{{ $user->role_id }}">Admin</option>
                                @elseif($user->role_id == 2)
                                        <option selected value="{{ $user->role_id }}">Bos</option>
                                    @elseif($user->role_id == 3)
                                        <option selected value="{{ $user->role_id }}">HR</option>
                                    @elseif($user->role_id == 4)
                                        <option selected value="{{ $user->role_id }}">Pegawai</option>
                                        @elseif($user->role_id == 5)
                                        <option selected value="{{ $user->role_id }}">Trainer</option>
                                        @elseif($user->role_id == 6)
                                        <option selected value="{{ $user->role_id }}">QC</option>
                                    @elseif($user->role_id == 7)
                                    <option selected value="{{ $user->role_id }}">Produksi</option>
                                    @else
                                    <option selected value="{{ $user->role_id }}">Umum</option>
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
                            <div class="mb-2 mt-3 item-center text-center">

                                <button type="submit" class="btn btn- m-0 bg-gradient-info rounded-0 border-primary">Save
                                </button>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                
            </div>
            
        </form>

        
        <script src="https://code.jquery.com/jquery-3.6.0.slim.js"
            integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
        </script>
        <link rel="stylesheet" type="text/css"
            href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <script type="text/javascript"></script>

        <script>
            $(function() {
                var count = {{ $jmlh_data_d }} + 1;

                // if (count == 0) {
                //     $('.btnSave').hide();
                // }
                $('#btn-tambah').on('click', function() {
                    count += 1;
                    $('#tbl-barang-body').append(`
<tr>
<td hidden >` + count + `</td>            
<td hidden >
            <input type="text" name="int_akses[` + (count - 1) + `]" value="{{ $int_akses }}[` + (count - 1) + `]" class="form-control form-control-sm" >
        </td>
        <td>
            <select class="form-control form-control-sm rounded-0" name="bagian[` + (count - 1) + `]" id="">
                       @foreach ($bag as $bagb)
                        <option value="{{ $bagb->bagian }}">{{ $bagb->bagian }}</option>
                         @endforeach
                 </select>        
            </td>
            <td class="text-center">
    <button class="btn removeItem m-0 btn-md btn-transparent">
        <i class="fas fa-trash text-danger"></i>
    </button>
</td>
</tr>
`);

                    $('.removeItem').on('click', function() {
                        $(this).closest("tr").remove();
                        count -= 1;

                    })
                })

            })
        </script>

        
    @endsection


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