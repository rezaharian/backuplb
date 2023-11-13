@extends('hr.dashboard.training.trainer.layout.layout')

@section('content')
    <form class="px-4" method="POST">
        @csrf
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
            <div class="alert alert-success text-center">
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif
            <div class="col-md-4">
                <table class="table">
                    <div class="row ms-3 bg-gradien-light  pt-3 text-center item-center justify-content-center">
                        <div class="col-md-2">
                            <a href="/hr/dashboard/training/tr/print/{{ $view->id }}" target="_blank">
                                <button type="button" class="btn btn-sm btn-info" id="btn-tambah">Print </button>
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('hr.training.delete', $view->id) }}" onclick="return confirm('Apakah Anda Yakin ?');" class="btn btn-sm btn-danger">Delete</a>
                        </div>
                        <div class="col-md-2 ms-2">
                            @if($view->approve == 'ya'  )
                            <a  href="{{ route('trainer.edit', $view->id) }}" class="btn btn-sm btn-primary disabled">Edit</a>
                            @else  
                            <a  href="{{ route('trainer.edit', $view->id) }}" class="btn btn-sm btn-primary ">Edit</a>
                            @endif
                        </div>
                    </div>
                    <tr>
                        <td><label for="example-text-input" class="form-control-label">NO Pelatihan</label></td>
                        <td><input disabled class="form-control" type="text" name="train_cod"
                                value="{{ $view->train_cod }}">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="example-text-input" class="form-control-label">Tempat</label></td>
                        <td><input disabled class="form-control" type="text" name="tempat" value="{{ $view->tempat }}">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="example-text-input disabled" class="form-control-label">Pemateri</label></td>
                        <td><input disabled class="form-control" type="text" name="pemateri"
                                value="{{ $view->pemateri }}"></td>
                    </tr>
                    <tr>
                        <td><label for="example-text-input disabled" class="form-control-label">Pelatihan</label></td>
                        <td>
                            <textarea disabled class="form-control" type="text" name="pltran_nam" value="{{ $view->pltran_nam }}">{{ $view->pltran_nam }} </textarea>
                        </td>

                    </tr>
                </table>
            </div>
            <div class="col-md-6">

                <table class="table mt-5">

                    <tr>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td><label for="example-text-input disabled" class="form-control-label">Tipe</label></td>
                        <td>
                            <select disabled class="form-select" name="tipe" id="package"
                                aria-label="Default select example">
                                <option selected></option>
                                <option selected value="Training">Training</option>
                                <option value="Sosialisasi">Sosialisasi</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td><label for="example-text-input disabled" class="form-control-label">Kompetensi</label></td>
                        <td>
                            <select disabled class="form-select" name="kompetensi" aria-label="Default select example">
                                <option selected>{{ $view->kompetensi }}</option>

                            </select>
                        </td>
                    </tr>


                    <tr>
                        <td><label for="example-text-input disabled" class="form-control-label">tanggal</label></td>
                        <td><input disabled class="form-control" type="date" name="train_dat"
                                value="{{ $view->train_dat }}"></td>
                    </tr>
                    <tr>
                        <td><label for="example-text-input disabled" class="form-control-label">Jam</label></td>
                        <td>
                            <div class="row">
                                <div class="col-md-4">
                                    <input disabled class="form-control md-2" type="time" name="jam"
                                        value="{{ $view->jam }}">
                                </div>s/d
                                <div class="col-md-4">
                                    <input disabled class="form-control" type="time" name="sdjam"
                                        value="{{ $view->sdjam }}">
                        </td>
            </div>
        </div>


        </tr>
        <tr>
            <td><label for="example-text-input disabled" class="form-control-label">Materi Pelatihan</label></td>
            <td>
                <textarea disabled class="form-control" type="text" name="train_tema" value="{{ $view->train_tema }}">{{ $view->train_tema }} </textarea>
            </td>

        </tr>
        </table>

        </div>

        <div class="row">
            <div class="col-lg-12">
                <table class="table" style="width:100%;">
                    <thead class="bg-secondary text-light">
                        <th>No.</th>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Nilai</th>
                        <th>Keterangan</th>
                        <th>Cek HR</th>
                    </thead>
                    <tbody id="tbl-barang-body" >
                        @forelse ($view_d as $item)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $item->no_payroll }}</td>
                                <td>{{ $item->nama_asli }}</td>
                                <td>{{ $item->nilai }}</td>
                                <td>{{ $item->keterangan }}</td>
                                <td>{{ $item->approve }}</td>
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
            <div class="col-md-8">

            </div>
            <div class="col-md-4">
                <label for="example-text-input" class="form-control-label">Ferisikasi HR :</label>
                <select disabled class="form-select" name="approve_h" aria-label="Default select example">
                    <option selected>{{ $view->approve }}</option>
                </select>
            </div>
        </div>
    </form>
@endsection
