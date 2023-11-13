@extends('hr.dashboard.layout.layout')

@section('content')
    <div class="row card-plain">
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
            <div class="alert alert-info text-center px-5">
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif
        <div class="col-md-4">
            <div class="card card-plain rounded-0 border-primary">
                <div class="card-header pb-0 bg-primary rounded-0">
                    <h4 class="font-weight-bolder text-light">Tambah Target</h4>
                </div>
                <div class="card-body">
                    <form action="/hr/dashboard/training/target/create" method="POST">
                        @csrf
                        <div class="form-group m-0">
                            <label for="tgl_input" class="text-secondary fs-xxl m-0">Tgl Input</label>
                            <input class="form-control form-control-sm" type="date" name="tgl_input" value="">
                        </div>

                        <div class="form-group m-0">
                            <label for="bagian" class="text-secondary fs-xxl m-0">Bagian</label>
                            <select class="form-control form-control-sm" type="text" name="bagian" value="">
                                @foreach ($bagian as $item_bg)
                                    <option value="{{ $item_bg->bagian }}">{{ $item_bg->bagian }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group m-0">
                            <label for="jumlah_jam" class="text-secondary fs-xxl m-0">Jumlah Jam Target</label>
                            <input class="form-control form-control-sm" type="text" name="jumlah_jam" value="">
                        </div>

                        <div class="form-group m-0">
                            <label for="periode_awal" class="text-secondary fs-xxl m-0">Periode Awal</label>
                            <input class="form-control form-control-sm" type="date" name="periode_awal" value="">
                        </div>

                        <div class="form-group m-0">
                            <label for="periode_akhir" class="text-secondary fs-xxl m-0">Periode Akhir</label>
                            <input class="form-control form-control-sm" type="date" name="periode_akhir" value="">
                        </div>

                        <div class="form-group m-0">
                            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        <div class="col-md-8">
            <div class="card card-plain rounded-0 border-primary">
                <div style="height: 526px; overflow: auto;">
                    <table class="table table-sm table-hover" style="font-size: 10pt;">
                        <thead class="bg-primary text-light sticky-top">
                            <tr class="fw-bold">
                                <th scope="col">No</th>
                                <th scope="col">Tgl Input</th>
                                <th scope="col">Bagian</th>
                                <th scope="col">Target</th>
                                <th scope="col">Pr Awal</th>
                                <th scope="col">Pr Akhir</th>
                                <th scope="col">Opt</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 9pt;" class="text-secondary">
                            @foreach ($target as $item)
                                <tr class="text-secondary">
                                    <th class="text-center">{{ $loop->iteration }}</th>
                                    <td>{{ $item->tgl_input_f }}</td>
                                    <td>{{ $item->bagian }}</td>
                                    <td>{{ $item->jumlah_jam }}</td>
                                    <td>{{ $item->periode_awal_f }}</td>
                                    <td>{{ $item->periode_akhir_f }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('hr.training.target.delete', $item->id) }}"
                                            onclick="return confirm('Apakah Anda Yakin ?');"
                                            class="btn btn-sm m-0 btn-delete text-danger bg-light">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                        <a href="{{ route('hr.training.target.edit', $item->id) }}"
                                            class="btn btn-sm m-0 btn-editt btn-light btn-edit1  text-primary">
                                            <i class="fa-solid fa-pen "></i>
                                        </a>

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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var formCard = document.querySelector(".card-plain");
            formCard.style.opacity = "1";
        });
        document.addEventListener("DOMContentLoaded", function() {
            var formCard = document.querySelector(".card-plain2");
            formCard.style.opacity = "1";
        });
    </script>

    <style>
        .btn-delete {
            padding: 0.25rem 0.5rem !important;
        }

        .btn-delete:hover {
            background-color: red !important;
            color: white !important;
        }

        .btn-edit1 {
            padding: 0.25rem 0.5rem !important;
        }

        .btn-edit1:hover {
            background-color: blue !important;
            color: rgb(255, 255, 255) !important;
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
    </style>

@endsection
