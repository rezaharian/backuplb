@extends('hr.dashboard.layout.layout')

@section('content')
    <form class="px-2" method="POST">
        @csrf
        <div class="card card-plain border-primary rounded-0 pt-2 mb-2">

            <div class="row">
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

                <div class="col-md-1   text-center">

                    <div class="row">
                        <div class="row mb-2  justify-content-center">
                            <div>
                                <div class="col-md-1 m-1">
                                    <a href="{{ route('hr.training.delete', $view->id) }}"
                                        onclick="return confirm('Apakah Anda Yakin ?');"><i class="fas fa-trash text-danger"></i></a>
                                </div>
                                <div class="col-md-1 m-1">
                                    <a href="/hr/dashboard/training/tr/print/{{ $view->id }}" target="_blank"><i
                                            class="fas fa-print"></i> </a>
                                </div>
                                <div class="col-md-1 m-1 ">
                                    <a href="{{ route('hr.training.edit', $view->id) }}">
                                        <i class="fas fa-pen"></i></a>

                                </div>
                                <div class="col-md-1 m-1 ">
                                    <a href="https://docs.google.com/gview?url={{ url($url) }}&embedded=true"
                                        target="_blank">
                                        <i class="fas fa-folder"></i></a>

                                </div>
                                <div class="col-md-1 m-1 ">
                                    <a href="/files_absen/{{ $view->file_absen }}" target="_blank">
                                        <i class="fas fa-list"></i></a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <table class="table table-sm">
                        <tr>
                            <td><label for="example-text-input" class="form-control-label text-secondary">NO
                                    Pelatihan</label>
                            </td>
                            <td><input disabled class="form-control form-control-sm" type="text" name="train_cod"
                                    value="{{ $view->train_cod }}"></td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input" class="form-control-label text-secondary">Tempat</label>
                            </td>
                            <td><input disabled class="form-control form-control-sm" type="text" name="tempat"
                                    value="{{ $view->tempat }}"></td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input disabled"
                                    class="form-control-label text-secondary">Pemateri</label></td>
                            <td><input disabled class="form-control form-control-sm" type="text" name="pemateri"
                                    value="{{ $view->pemateri }}"></td>
                        </tr>

                    </table>
                </div>
                <div class="col-md-3">
                    <table class="table table-sm">
                        <tr>
                            <td><label for="example-text-input disabled"
                                    class="form-control-label text-secondary">Pelatihan</label></td>
                            <td>
                                <textarea disabled class="form-control form-control-sm" type="text" name="pltran_nam">{{ $view->pltran_nam }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="example-text-input disabled"
                                    class="form-control-label text-secondary">Tipe</label>
                            </td>
                            <td><input disabled class="form-control form-control-sm" type="text" name="pemateri"
                                    value="{{ $view->tipe }}"></td>
                        </tr>


                    </table>

                </div>
                <div class="col-md-4">

                    <table class="table table-sm">



                        <tr hidden>
                            <td><label for="example-text-input disabled"
                                    class="form-control-label text-secondary">Kompetensi</label></td>
                            <td>
                                <select disabled class="form-select form-select-sm" name="kompetensi"
                                    aria-label="Default select example">
                                    <option selected>{{ $view->kompetensi }}</option>

                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label for="example-text-input disabled"
                                    class="form-control-label text-secondary">tanggal</label></td>
                            <td><input disabled class="form-control form-control-sm" type="date" name="train_dat"
                                    value="{{ $view->train_dat }}"></td>
                        </tr>

                        <tr>
                            <td><label for="example-text-input disabled"
                                    class="form-control-label text-secondary">Jam</label>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-md-5">
                                        <input disabled class="form-control form-control-sm md-2" type="time"
                                            name="jam" value="{{ $view->jam }}">
                                    </div>s/d
                                    <div class="col-md-5">
                                        <input disabled class="form-control form-control-sm" type="time" name="sdjam"
                                            value="{{ $view->sdjam }}">
                            </td>

                        </tr>
                        <tr>
                            <td><label for="example-text-input disabled" class="form-control-label text-secondary">Materi
                                    Pelatihan</label>
                            </td>
                            <td>
                                <textarea disabled class="form-control form-control-sm" type="text" name="train_tema"
                                    value="{{ $view->train_tema }}">{{ $view->train_tema }} </textarea>
                            </td>

                        </tr>
                    </table>

                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-sm">
                        <thead class="bg-primary text-center text-light shadow font-weight-bolder kepala-tabel">
                            <th>No.</th>
                            <th>NIK</th>
                            <th>Nama Karyawan</th>
                            <th>Nilai Pretest</th>
                            <th>Nilai Post Test</th>
                            <th>Keterangan</th>
                            <th>Cek HR</th>
                        </thead>
                        <tbody id="tbl-barang-body">
                            @forelse ($view_d as $item)
                                <tr>
                                    <th class="small">{{ $loop->iteration }}</th>
                                    <td class="small">{{ $item->no_payroll }}</td>
                                    <td class="small">{{ $item->nama_asli }}</td>
                                    <td class="small">{{ $item->nilai_pre }}</td>
                                    <td class="small">{{ $item->nilai }}</td>
                                    <td class="small">{{ $item->keterangan }}</td>
                                    <td class="small">{{ $item->approve }}</td>
                                </tr>
                            @empty
                                <td class="text-center">
                                    <h3>Tidak Ada Data . . . </h3>
                                </td>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>

            <div class="row btnSave" style="display:none;">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary">SIMPAN </button>
                </div>
            </div>

        </div>
        <div class="row px-4">
            <div class="col-md-10">

            </div>
            <div class="col-md-2 card-plain">
                <label for="example-text-input" class="form-control-label">Ferisikasi HR :</label>
                <select disabled class="form-select form-select-sm" name="approve_h" aria-label="Default select example">
                    <option selected>{{ $view->approve }}</option>
                </select>
            </div>
        </div>
    </form>
    <style>
        .kepala-tabel {
            font-size: 12px;
        }

        .small {
            font-size: 10px;
            color: slategray;
        }

        tbody#tbl-barang-body td.small {
            padding: 5px;
            margin: 5px;

        }

            .form-control{
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
